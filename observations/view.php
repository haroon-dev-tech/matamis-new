<?php
$pageTitle = 'Observation Details';
$activeNav = 'observations';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
ensure_company_observations_table($db);

$companyId = (int) ($_GET['company_id'] ?? 0);
$observationId = (int) ($_GET['id'] ?? 0);

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

$pageTitle = $observation['head'] . ' — ' . $company['name'];

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/observations/index.php?company_id=<?= $companyId ?>" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Observations
    </a>
    <a href="<?= BASE_URL ?>/observations/edit.php?id=<?= $observationId ?>&company_id=<?= $companyId ?>" class="btn-primary text-sm">Edit Observation</a>
</div>

<div class="card p-8">
    <div class="mb-6 border-b border-slate-200 pb-6 dark:border-slate-800">
        <p class="text-sm text-slate-500"><?= e($company['name']) ?></p>
        <h2 class="mt-1 text-xl font-semibold"><?= e($observation['head']) ?></h2>
        <?php if (!empty($observation['status'])): ?>
        <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
            <?= e($observation['status']) ?>
        </span>
        <?php endif; ?>
    </div>

    <dl class="space-y-6 text-sm">
        <div>
            <dt class="mb-1 font-medium text-slate-500">Details</dt>
            <dd class="whitespace-pre-wrap"><?= e($observation['details'] ?: '—') ?></dd>
        </div>
        <div>
            <dt class="mb-1 font-medium text-slate-500">Risk</dt>
            <dd class="whitespace-pre-wrap"><?= e($observation['risk'] ?: '—') ?></dd>
        </div>
        <div>
            <dt class="mb-1 font-medium text-slate-500">Recommendations</dt>
            <dd class="whitespace-pre-wrap"><?= e($observation['recommendations'] ?: '—') ?></dd>
        </div>
        <div class="grid gap-4 border-t border-slate-200 pt-6 dark:border-slate-800 sm:grid-cols-2">
            <div>
                <dt class="text-slate-500">Created</dt>
                <dd class="font-medium"><?= date('d M Y H:i', strtotime($observation['created_at'])) ?></dd>
            </div>
            <div>
                <dt class="text-slate-500">Last Updated</dt>
                <dd class="font-medium"><?= date('d M Y H:i', strtotime($observation['updated_at'])) ?></dd>
            </div>
        </div>
    </dl>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
