<?php
$pageTitle = 'Companies';
$activeNav = 'companies';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_company_id'])) {
    if (!verify_csrf()) {
        flash('error', 'Invalid security token.');
        redirect('/companies/index.php');
    }

    $companyId = (int) $_POST['delete_company_id'];
    if (!user_owns_company($db, $companyId, $userId)) {
        flash('error', 'Company not found.');
        redirect('/companies/index.php');
    }

    try {
        $db->beginTransaction();
        soft_delete_company($db, $companyId);
        $db->commit();
        flash('success', 'Company deleted successfully.');
    } catch (Exception $e) {
        $db->rollBack();
        flash('error', 'Failed to delete company.');
    }

    redirect('/companies/index.php');
}

$stmt = $db->prepare(
    'SELECT c.*, COUNT(b.id) as branch_count
     FROM companies c
     LEFT JOIN branches b ON b.company_id = c.id AND b.deleted_at IS NULL
     WHERE c.user_id = ? AND c.deleted_at IS NULL
     GROUP BY c.id
     ORDER BY c.name ASC'
);
$stmt->execute([$userId]);
$companies = $stmt->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">Manage registered companies and their branches</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Register Company
    </a>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
    </div>
    <h3 class="text-lg font-semibold">No companies yet</h3>
    <p class="mt-1 text-sm text-slate-500">Register your first company to start entering financial data.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-6">Register Company</a>
</div>
<?php else: ?>
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-6 py-3 text-left font-semibold">Company Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Trade License</th>
                    <th class="px-6 py-3 text-center font-semibold">Branches</th>
                    <th class="px-6 py-3 text-left font-semibold">Registered</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($companies as $co): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-medium"><?= e($co['name']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= e($co['trade_license'] ?: '—') ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950 dark:text-brand-300"><?= (int)$co['branch_count'] ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-500"><?= date('d M Y', strtotime($co['created_at'])) ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="table-actions">
                            <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $co['id'] ?>" class="btn-action btn-action-view">View</a>
                            <span class="table-action-sep">|</span>
                            <a href="<?= BASE_URL ?>/companies/edit.php?id=<?= $co['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                            <span class="table-action-sep">|</span>
                            <form method="POST" class="inline" onsubmit="return confirm('Delete this company and all its branches and financial data?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="delete_company_id" value="<?= $co['id'] ?>">
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

<?php require __DIR__ . '/../includes/footer.php'; ?>
