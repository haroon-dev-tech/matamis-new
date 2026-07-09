<?php
$pageTitle = 'Observations & Recommendations';
$activeNav = 'observations';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
ensure_company_observations_table($db);

$stmt = $db->prepare('SELECT id, name FROM companies WHERE user_id = ? AND ' . not_deleted() . ' ORDER BY name ASC');
$stmt->execute([$userId]);
$companies = $stmt->fetchAll();

$selectedCompanyId = (int) ($_GET['company_id'] ?? ($companies[0]['id'] ?? 0));
$observations = [];
$selectedCompany = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_observation_id'])) {
    if (!verify_csrf()) {
        flash('error', 'Invalid security token.');
        redirect('/observations/index.php' . ($selectedCompanyId ? '?company_id=' . $selectedCompanyId : ''));
    }

    $observationId = (int) $_POST['delete_observation_id'];
    $companyId = (int) ($_POST['company_id'] ?? 0);

    if (!$companyId || !user_owns_company($db, $companyId, $userId)) {
        flash('error', 'Company not found.');
        redirect('/observations/index.php');
    }

    $observation = get_company_observation($db, $observationId, $companyId);
    if (!$observation) {
        flash('error', 'Observation not found.');
        redirect('/observations/index.php?company_id=' . $companyId);
    }

    soft_delete_rows($db, 'company_observations', 'id = ? AND company_id = ?', [$observationId, $companyId]);
    flash('success', 'Observation deleted successfully.');
    redirect('/observations/index.php?company_id=' . $companyId);
}

if ($selectedCompanyId && user_owns_company($db, $selectedCompanyId, $userId)) {
    $stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
    $stmt->execute([$selectedCompanyId]);
    $selectedCompany = $stmt->fetch();
    $observations = get_company_observations($db, $selectedCompanyId);
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Record observations, risks, and recommendations for each registered company
    </p>
    <?php if ($selectedCompanyId): ?>
    <a href="<?= BASE_URL ?>/observations/create.php?company_id=<?= $selectedCompanyId ?>" class="btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Observation
    </a>
    <?php endif; ?>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to add observations and recommendations.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php else: ?>
<div class="card mb-6 p-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="min-w-[240px] flex-1">
            <label class="mb-1.5 block text-sm font-medium">Company (Project)</label>
            <select name="company_id" class="input-field" onchange="this.form.submit()">
                <?php foreach ($companies as $co): ?>
                <option value="<?= $co['id'] ?>" <?= $co['id'] == $selectedCompanyId ? 'selected' : '' ?>><?= e($co['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if (empty($observations)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No observations recorded for <?= e($selectedCompany['name'] ?? 'this company') ?> yet.</p>
    <a href="<?= BASE_URL ?>/observations/create.php?company_id=<?= $selectedCompanyId ?>" class="btn-primary mt-4">Add First Observation</a>
</div>
<?php else: ?>
<?php
$cellText = static function (?string $text): string {
    $text = trim((string) $text);
    return $text === '' ? '—' : $text;
};
?>
<div class="card overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h2 class="font-semibold"><?= e($selectedCompany['name']) ?> — Observations (<?= count($observations) ?>)</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="observations-table w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-4 py-3 text-left font-semibold">Head</th>
                    <th class="px-4 py-3 text-left font-semibold">Details</th>
                    <th class="px-4 py-3 text-left font-semibold">Risk</th>
                    <th class="px-4 py-3 text-left font-semibold">Recommendations</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($observations as $row): ?>
                <?php $status = $row['status'] ?? ''; ?>
                <tr class="align-top hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-4 py-4 font-medium text-slate-900 dark:text-white"><?= e($row['head']) ?></td>
                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                        <div class="observations-cell-text whitespace-pre-wrap"><?= e($cellText($row['details'] ?? null)) ?></div>
                    </td>
                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                        <div class="observations-cell-text whitespace-pre-wrap"><?= e($cellText($row['risk'] ?? null)) ?></div>
                    </td>
                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                        <div class="observations-cell-text whitespace-pre-wrap"><?= e($cellText($row['recommendations'] ?? null)) ?></div>
                    </td>
                    <td class="px-4 py-4">
                        <?php if ($status): ?>
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            <?= e($status) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="table-actions justify-end">
                            <a href="<?= BASE_URL ?>/observations/edit.php?id=<?= $row['id'] ?>&company_id=<?= $selectedCompanyId ?>" class="btn-action btn-action-edit">Edit</a>
                            <span class="table-action-sep">|</span>
                            <form method="POST" class="inline" onsubmit="return confirm('Delete this observation?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="company_id" value="<?= $selectedCompanyId ?>">
                                <input type="hidden" name="delete_observation_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn-action btn-action-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
