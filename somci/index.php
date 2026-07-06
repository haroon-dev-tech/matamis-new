<?php
$pageTitle = 'SOMCI — Statement of Monthly Comprehensive Income';
$activeNav = 'somci';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();

$stmt = $db->prepare('SELECT id, name FROM companies WHERE user_id = ? AND ' . not_deleted() . ' ORDER BY name ASC');
$stmt->execute([$userId]);
$companies = $stmt->fetchAll();

$selectedCompanyId = (int) ($_GET['company_id'] ?? ($companies[0]['id'] ?? 0));
$selectedYear = (int) ($_GET['year'] ?? date('Y'));
$selectedMonth = (int) ($_GET['month'] ?? date('n'));

$entries = [];
if ($selectedCompanyId && user_owns_company($db, $selectedCompanyId, $userId)) {
    $stmt = $db->prepare(
        'SELECT b.id as branch_id, b.name as branch_name, b.is_head_office,
                COUNT(se.id) as entry_count,
                MAX(se.updated_at) as last_updated
         FROM branches b
         LEFT JOIN somci_entries se ON se.branch_id = b.id
             AND se.period_year = ? AND se.period_month = ? AND se.deleted_at IS NULL
         WHERE b.company_id = ? AND b.deleted_at IS NULL
         GROUP BY b.id
         ORDER BY b.is_head_office DESC, b.name ASC'
    );
    $stmt->execute([$selectedYear, $selectedMonth, $selectedCompanyId]);
    $entries = $stmt->fetchAll();
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Monthly Comprehensive Income (SOMCI)
    </p>
</div>

<div class="card mb-6 p-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="min-w-[200px] flex-1">
            <label class="mb-1.5 block text-sm font-medium">Company</label>
            <select name="company_id" class="input-field" onchange="this.form.submit()">
                <?php foreach ($companies as $co): ?>
                <option value="<?= $co['id'] ?>" <?= $co['id'] == $selectedCompanyId ? 'selected' : '' ?>><?= e($co['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-32">
            <label class="mb-1.5 block text-sm font-medium">Year</label>
            <select name="year" class="input-field">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="w-40">
            <label class="mb-1.5 block text-sm font-medium">Month</label>
            <select name="month" class="input-field">
                <?php foreach (MONTHS as $m => $label): ?>
                <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary">Filter</button>
        <?php if ($selectedCompanyId): ?>
        <a href="<?= BASE_URL ?>/somci/entry.php?company_id=<?= $selectedCompanyId ?>&year=<?= $selectedYear ?>&month=<?= $selectedMonth ?>" class="btn-secondary">New / Edit Entry</a>
        <a href="<?= BASE_URL ?>/somci/view.php?company_id=<?= $selectedCompanyId ?>&year=<?= $selectedYear ?>&month=<?= $selectedMonth ?>" class="btn-secondary">Consolidated View</a>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to enter SOMCI data.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php elseif (!empty($entries)): ?>
<div class="card overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h2 class="font-semibold"><?= e(MONTHS[$selectedMonth]) ?> <?= $selectedYear ?> — Branch Status</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-6 py-3 text-left font-semibold">Branch</th>
                    <th class="px-6 py-3 text-center font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Last Updated</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($entries as $entry): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-medium">
                        <?= e($entry['branch_name']) ?>
                        <?php if ($entry['is_head_office']): ?>
                        <span class="ml-1 text-xs text-brand-600">(HO)</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($entry['entry_count'] > 0): ?>
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">Entered</span>
                        <?php else: ?>
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-slate-500">
                        <?= $entry['last_updated'] ? date('d M Y H:i', strtotime($entry['last_updated'])) : '—' ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="table-actions">
                            <a href="<?= BASE_URL ?>/soci/view.php?company_id=<?= $selectedCompanyId ?>&year=<?= $selectedYear ?>&month=<?= $selectedMonth ?>&branch_id=<?= $entry['branch_id'] ?>" class="btn-action btn-action-view">View</a>
                            <span class="table-action-sep">|</span>
                            <a href="<?= BASE_URL ?>/somci/entry.php?company_id=<?= $selectedCompanyId ?>&branch_id=<?= $entry['branch_id'] ?>&year=<?= $selectedYear ?>&month=<?= $selectedMonth ?>" class="btn-action btn-action-edit">
                                <?= $entry['entry_count'] > 0 ? 'Edit' : 'Enter' ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
