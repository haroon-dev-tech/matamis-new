<?php
$pageTitle = 'Edit Company';
$activeNav = 'companies';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$companyId = (int) ($_GET['id'] ?? 0);
if (!$companyId || !user_owns_company($db, $companyId, current_user_id())) {
    flash('error', 'Company not found.');
    redirect('/companies/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();
$branches = get_company_branches($db, $companyId);

$error = null;
$oldLogoPath = $company['logo_path'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $tradeLicense = trim($_POST['trade_license'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $branchData = $_POST['branches'] ?? [];
        $deleteIds = array_map('intval', $_POST['delete_branch'] ?? []);
        $removeLogo = !empty($_POST['remove_logo']);

        $uploadedLogoPath = null;
        $uploadedLogoAbsPath = null;

        if (isset($_FILES['logo']) && (int) $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ((int) $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Failed to upload logo image.';
            } else {
                $maxLogoSize = 2 * 1024 * 1024; // 2MB
                if ((int) $_FILES['logo']['size'] > $maxLogoSize) {
                    $error = 'Logo image must be 2MB or smaller.';
                } else {
                    $tmpPath = $_FILES['logo']['tmp_name'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo ? finfo_file($finfo, $tmpPath) : '';
                    if ($finfo) {
                        finfo_close($finfo);
                    }

                    $allowedMimeTypes = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                        'image/gif' => 'gif',
                    ];

                    if (!isset($allowedMimeTypes[$mimeType])) {
                        $error = 'Only JPG, PNG, WEBP, or GIF logos are allowed.';
                    } else {
                        $uploadDir = __DIR__ . '/../uploads/company-logos';
                        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                            $error = 'Unable to create logo upload directory.';
                        } else {
                            $extension = $allowedMimeTypes[$mimeType];
                            $logoFilename = 'logo_' . bin2hex(random_bytes(8)) . '.' . $extension;
                            $destinationPath = $uploadDir . '/' . $logoFilename;

                            if (!move_uploaded_file($tmpPath, $destinationPath)) {
                                $error = 'Unable to save uploaded logo.';
                            } else {
                                $uploadedLogoAbsPath = $destinationPath;
                                $uploadedLogoPath = '/uploads/company-logos/' . $logoFilename;
                            }
                        }
                    }
                }
            }
        }

        if ($error !== null) {
            // Keep validation error set above.
        } elseif ($name === '') {
            $error = 'Company name is required.';
        } else {
            $validBranches = [];
            foreach ($branchData as $bid => $b) {
                $bName = trim($b['name'] ?? '');
                if ($bName !== '') {
                    $validBranches[] = [
                        'id' => (is_numeric($bid) && (int)$bid > 0) ? (int) $bid : null,
                        'name' => $bName,
                        'location' => trim($b['location'] ?? ''),
                        'is_head_office' => !empty($b['is_head_office']) ? 1 : 0,
                    ];
                }
            }
            if (empty($validBranches)) {
                $error = 'At least one branch is required.';
            } else {
                try {
                    // Ensure we have the logo column (for older DBs).
                    $hasLogoColumnStmt = $db->query("SHOW COLUMNS FROM companies LIKE 'logo_path'");
                    $hasLogoColumn = (bool) $hasLogoColumnStmt->fetch();
                    if (!$hasLogoColumn) {
                        $db->exec('ALTER TABLE companies ADD COLUMN logo_path VARCHAR(255) NULL AFTER address');
                    }

                    $newLogoPathToSave = $oldLogoPath;
                    if (!empty($uploadedLogoPath)) {
                        // Replace existing logo.
                        $newLogoPathToSave = $uploadedLogoPath;
                    } elseif ($removeLogo) {
                        // Remove without replacement.
                        $newLogoPathToSave = null;
                    }

                    $shouldDeleteOldLogo = ($removeLogo || !empty($uploadedLogoPath)) && !empty($oldLogoPath);
                    $oldLogoAbsPath = $shouldDeleteOldLogo ? (__DIR__ . '/..' . $oldLogoPath) : null;

                    $db->beginTransaction();
                    $stmt = $db->prepare('UPDATE companies SET name = ?, trade_license = ?, address = ?, logo_path = ? WHERE id = ?');
                    $stmt->execute([$name, $tradeLicense ?: null, $address ?: null, $newLogoPathToSave, $companyId]);

                    if (!empty($deleteIds)) {
                        soft_delete_branches($db, $deleteIds, $companyId);
                    }

                    $updateStmt = $db->prepare('UPDATE branches SET name = ?, location = ?, is_head_office = ? WHERE id = ? AND company_id = ?');
                    $insertStmt = $db->prepare('INSERT INTO branches (company_id, name, location, is_head_office) VALUES (?, ?, ?, ?)');

                    foreach ($validBranches as $b) {
                        if ($b['id']) {
                            $updateStmt->execute([$b['name'], $b['location'] ?: null, $b['is_head_office'], $b['id'], $companyId]);
                        } else {
                            $insertStmt->execute([$companyId, $b['name'], $b['location'] ?: null, $b['is_head_office']]);
                        }
                    }

                    $db->commit();

                    // Delete old logo file only after DB update succeeds.
                    if ($shouldDeleteOldLogo && $oldLogoAbsPath && is_file($oldLogoAbsPath)) {
                        @unlink($oldLogoAbsPath);
                    }
                    flash('success', 'Company updated successfully.');
                    redirect('/companies/view.php?id=' . $companyId);
                } catch (Exception $e) {
                    $db->rollBack();
                    if (!empty($uploadedLogoAbsPath) && is_file($uploadedLogoAbsPath)) {
                        // Avoid orphaning the uploaded file if DB update fails.
                        @unlink($uploadedLogoAbsPath);
                    }
                    $error = 'Failed to update company.';
                }
            }
        }
    }
    $branches = get_company_branches($db, $companyId);
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="mx-auto max-w-3xl">
    <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $companyId ?>" class="mb-6 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Company
    </a>

    <div class="card p-8">
        <h2 class="mb-6 text-lg font-semibold">Edit Company</h2>

        <?php if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input-field" value="<?= e($_POST['name'] ?? $company['name']) ?>" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Trade License No.</label>
                    <input type="text" name="trade_license" class="input-field" value="<?= e($_POST['trade_license'] ?? $company['trade_license'] ?? '') ?>">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Address</label>
                    <textarea name="address" class="input-field" rows="2"><?= e($_POST['address'] ?? $company['address'] ?? '') ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Company Logo</label>
                    <?php if (!empty($company['logo_path'])): ?>
                    <div class="mb-3">
                        <img
                            src="<?= e(BASE_URL . $company['logo_path']) ?>"
                            alt="<?= e($company['name']) ?> logo"
                            class="h-16 w-16 rounded-lg border border-slate-200 object-contain p-1 dark:border-slate-700"
                        >
                    </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="input-field" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <input
                            type="checkbox"
                            name="remove_logo"
                            value="1"
                            class="rounded border-slate-300 text-brand-600"
                            id="remove-logo"
                        >
                        <label for="remove-logo" class="text-slate-700 dark:text-slate-200">Remove existing logo</label>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Optional. Max 2MB. JPG/PNG/WEBP/GIF.</p>
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-semibold">Branches</h3>
                    <button type="button" id="add-branch" class="btn-secondary text-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Branch
                    </button>
                </div>
                <div id="branch-list" class="space-y-3">
                    <?php foreach ($branches as $idx => $branch): ?>
                    <div class="branch-row flex flex-wrap items-end gap-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                        <div class="flex-1 min-w-[200px]">
                            <label class="mb-1 block text-xs font-medium text-slate-500">Branch Name</label>
                            <input type="text" name="branches[<?= $branch['id'] ?>][name]" class="input-field" value="<?= e($branch['name']) ?>" required>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="mb-1 block text-xs font-medium text-slate-500">Location</label>
                            <input type="text" name="branches[<?= $branch['id'] ?>][location]" class="input-field" value="<?= e($branch['location'] ?? '') ?>">
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <input type="checkbox" name="branches[<?= $branch['id'] ?>][is_head_office]" value="1" class="rounded border-slate-300 text-brand-600" <?= $branch['is_head_office'] ? 'checked' : '' ?>>
                            <label class="text-sm">Head Office</label>
                        </div>
                        <?php if (count($branches) > 1): ?>
                        <label class="flex items-center gap-1 pb-2 text-xs text-red-500">
                            <input type="checkbox" name="delete_branch[]" value="<?= $branch['id'] ?>" class="rounded border-red-300">
                            Delete
                        </label>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $companyId ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
