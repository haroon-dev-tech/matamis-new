<?php
$pageTitle = 'Register Company';
$activeNav = 'companies';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $tradeLicense = trim($_POST['trade_license'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $branches = $_POST['branches'] ?? [];
        $logoPath = null;

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
                                $logoPath = '/uploads/company-logos/' . $logoFilename;
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
            foreach ($branches as $b) {
                $bName = trim($b['name'] ?? '');
                if ($bName !== '') {
                    $validBranches[] = [
                        'name' => $bName,
                        'location' => trim($b['location'] ?? ''),
                        'is_head_office' => !empty($b['is_head_office']) ? 1 : 0,
                    ];
                }
            }
            if (empty($validBranches)) {
                $error = 'At least one branch is required.';
            } else {
                $savedLogoPath = $logoPath;
                try {
                    $db->beginTransaction();
                    $hasLogoColumnStmt = $db->query("SHOW COLUMNS FROM companies LIKE 'logo_path'");
                    $hasLogoColumn = (bool) $hasLogoColumnStmt->fetch();

                    if (!$hasLogoColumn) {
                        $db->exec('ALTER TABLE companies ADD COLUMN logo_path VARCHAR(255) NULL AFTER address');
                    }

                    $stmt = $db->prepare('INSERT INTO companies (user_id, name, trade_license, address, logo_path) VALUES (?, ?, ?, ?, ?)');
                    $stmt->execute([current_user_id(), $name, $tradeLicense ?: null, $address ?: null, $savedLogoPath]);
                    $companyId = (int) $db->lastInsertId();

                    $stmt = $db->prepare('INSERT INTO branches (company_id, name, location, is_head_office) VALUES (?, ?, ?, ?)');
                    foreach ($validBranches as $b) {
                        $stmt->execute([$companyId, $b['name'], $b['location'] ?: null, $b['is_head_office']]);
                    }
                    $db->commit();
                    flash('success', 'Company "' . $name . '" registered successfully.');
                    redirect('/companies/view.php?id=' . $companyId);
                } catch (Exception $e) {
                    $db->rollBack();
                    if (!empty($savedLogoPath)) {
                        $savedLogoAbsPath = __DIR__ . '/..' . $savedLogoPath;
                        if (is_file($savedLogoAbsPath)) {
                            @unlink($savedLogoAbsPath);
                        }
                    }
                    $error = 'Failed to register company. Please try again.';
                }
            }
        }
    }
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="mx-auto max-w-3xl">
    <a href="<?= BASE_URL ?>/companies/index.php" class="mb-6 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Companies
    </a>

    <div class="card p-8">
        <h2 class="mb-6 text-lg font-semibold">Company Details</h2>

        <?php if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input-field" value="<?= e($_POST['name'] ?? '') ?>" placeholder="e.g. Mata Consultancy LLC" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Trade License No.</label>
                    <input type="text" name="trade_license" class="input-field" value="<?= e($_POST['trade_license'] ?? '') ?>" placeholder="e.g. 123456">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Address</label>
                    <textarea name="address" class="input-field" rows="2" placeholder="Company address in UAE"><?= e($_POST['address'] ?? '') ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium">Company Logo</label>
                    <input type="file" name="logo" class="input-field" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <p class="mt-1 text-xs text-slate-500">Optional. Accepted: JPG, PNG, WEBP, GIF (max 2MB).</p>
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
                    <div class="branch-row flex flex-wrap items-end gap-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                        <div class="flex-1 min-w-[200px]">
                            <label class="mb-1 block text-xs font-medium text-slate-500">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" name="branches[0][name]" class="input-field" placeholder="e.g. Head Office" required>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="mb-1 block text-xs font-medium text-slate-500">Location</label>
                            <input type="text" name="branches[0][location]" class="input-field" placeholder="e.g. Dubai, UAE">
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <input type="checkbox" name="branches[0][is_head_office]" value="1" id="ho-0" class="rounded border-slate-300 text-brand-600" checked>
                            <label for="ho-0" class="text-sm">Head Office</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="<?= BASE_URL ?>/companies/index.php" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Register Company</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
