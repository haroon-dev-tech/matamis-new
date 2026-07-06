<?php
$pageTitle = 'Add Observation';
$activeNav = 'observations';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
ensure_company_observations_table($db);

$companyId = (int) ($_GET['company_id'] ?? $_POST['company_id'] ?? 0);
if (!$companyId || !user_owns_company($db, $companyId, $userId)) {
    flash('error', 'Please select a valid company.');
    redirect('/observations/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();

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
                    'INSERT INTO company_observations (company_id, head, details, risk, recommendations, status)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $companyId,
                    $head,
                    $details ?: null,
                    $risk ?: null,
                    $recommendations ?: null,
                    $status,
                ]);
                flash('success', 'Observation added successfully.');
                redirect('/observations/index.php?company_id=' . $companyId);
            } catch (Exception $e) {
                $error = 'Failed to save observation. Please try again.';
            }
        }
    }
}

$pageTitle = 'Add Observation — ' . $company['name'];

require __DIR__ . '/../includes/header.php';
?>

<div class="mx-auto max-w-3xl">
    <a href="<?= BASE_URL ?>/observations/index.php?company_id=<?= $companyId ?>" class="mb-6 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Observations
    </a>

    <div class="card p-8">
        <h2 class="mb-1 text-lg font-semibold">New Observation</h2>
        <p class="mb-6 text-sm text-slate-500"><?= e($company['name']) ?></p>

        <?php if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="company_id" value="<?= $companyId ?>">

            <div>
                <label class="mb-1.5 block text-sm font-medium">Head <span class="text-red-500">*</span></label>
                <input type="text" name="head" class="input-field" value="<?= e($_POST['head'] ?? '') ?>" placeholder="Observation heading" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Details</label>
                <textarea name="details" class="input-field" rows="4" placeholder="Detailed observation notes"><?= e($_POST['details'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Risk</label>
                <textarea name="risk" class="input-field" rows="4" placeholder="Identified risks"><?= e($_POST['risk'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Recommendations</label>
                <textarea name="recommendations" class="input-field" rows="4" placeholder="Recommended actions"><?= e($_POST['recommendations'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium">Status</label>
                <?php
                $selectedStatus = $_POST['status'] ?? 'Open';
                require __DIR__ . '/partials/status_select.php';
                ?>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="<?= BASE_URL ?>/observations/index.php?company_id=<?= $companyId ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Observation</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
