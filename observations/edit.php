<?php
$pageTitle = 'Edit Observation';
$activeNav = 'observations';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
ensure_company_observations_table($db);

$companyId = (int) ($_GET['company_id'] ?? $_POST['company_id'] ?? 0);
$observationId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$companyId || !$observationId || !user_owns_company($db, $companyId, $userId)) {
    flash('error', 'Observation not found.');
    redirect('/observations/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();

$observation = get_company_observation($db, $observationId, $companyId);
if (!$observation) {
    flash('error', 'Observation not found.');
    redirect('/observations/index.php?company_id=' . $companyId);
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } else {
        $head = trim($_POST['head'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $risk = trim($_POST['risk'] ?? '');
        $recommendations = trim($_POST['recommendations'] ?? '');
        $status = trim($_POST['status'] ?? 'Open');

        if ($head === '') {
            $error = 'Head is required.';
        } elseif (!in_array($status, OBSERVATION_STATUSES, true)) {
            $error = 'Please select a valid status.';
        } else {
            try {
                $stmt = $db->prepare(
                    'UPDATE company_observations
                     SET head = ?, details = ?, risk = ?, recommendations = ?, status = ?, updated_at = CURRENT_TIMESTAMP
                     WHERE id = ? AND company_id = ? AND deleted_at IS NULL'
                );
                $stmt->execute([
                    $head,
                    $details ?: null,
                    $risk ?: null,
                    $recommendations ?: null,
                    $status,
                    $observationId,
                    $companyId,
                ]);
                flash('success', 'Observation updated successfully.');
                redirect('/observations/view.php?id=' . $observationId . '&company_id=' . $companyId);
            } catch (Exception $e) {
                $error = 'Failed to update observation. Please try again.';
            }
        }
    }
}

$formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $observation;
$pageTitle = 'Edit Observation — ' . $company['name'];

require __DIR__ . '/../includes/header.php';
?>

<div class="mx-auto max-w-3xl">
    <a href="<?= BASE_URL ?>/observations/view.php?id=<?= $observationId ?>&company_id=<?= $companyId ?>" class="mb-6 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Observation
    </a>

    <div class="card p-8">
        <h2 class="mb-1 text-lg font-semibold">Edit Observation</h2>
        <p class="mb-6 text-sm text-slate-500"><?= e($company['name']) ?></p>

        <?php if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="company_id" value="<?= $companyId ?>">
            <input type="hidden" name="id" value="<?= $observationId ?>">

            <div>
                <label class="mb-1.5 block text-sm font-medium">Head <span class="text-red-500">*</span></label>
                <input type="text" name="head" class="input-field" value="<?= e($formData['head'] ?? '') ?>" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Details</label>
                <textarea name="details" class="input-field" rows="4"><?= e($formData['details'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Risk</label>
                <textarea name="risk" class="input-field" rows="4"><?= e($formData['risk'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Recommendations</label>
                <textarea name="recommendations" class="input-field" rows="4"><?= e($formData['recommendations'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Status</label>
                <?php
                $selectedStatus = $formData['status'] ?? 'Open';
                if (!in_array($selectedStatus, OBSERVATION_STATUSES, true)) {
                    $selectedStatus = 'Open';
                }
                require __DIR__ . '/partials/status_select.php';
                ?>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="<?= BASE_URL ?>/observations/view.php?id=<?= $observationId ?>&company_id=<?= $companyId ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Observation</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
