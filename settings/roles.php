<?php
$pageTitle = 'Roles';
$activeNav = 'settings-roles';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_roles', 'read');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_role_id'])) {
    require_permission($db, $userId, 'settings_roles', 'write');
    if (!verify_csrf()) {
        flash('error', 'Invalid security token.');
        redirect('/settings/roles.php');
    }

    $deleteId = (int) $_POST['delete_role_id'];
    try {
        $stmt = $db->prepare('UPDATE roles SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND deleted_at IS NULL');
        $stmt->execute([$deleteId]);
        $db->prepare('DELETE FROM role_permissions WHERE role_id = ?')->execute([$deleteId]);
        $db->prepare('DELETE FROM user_roles WHERE role_id = ?')->execute([$deleteId]);
        flash('success', 'Role deleted.');
    } catch (Exception $e) {
        flash('error', 'Failed to delete role.');
    }
    redirect('/settings/roles.php');
}

$roles = $db->query(
    'SELECT r.id, r.name, r.description, r.created_at,
            COUNT(DISTINCT ur.user_id) AS user_count
     FROM roles r
     LEFT JOIN user_roles ur ON ur.role_id = r.id
     WHERE r.deleted_at IS NULL
     GROUP BY r.id
     ORDER BY r.name ASC'
)->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <p class="text-sm text-slate-500 dark:text-slate-400">Manage roles and their permissions (read/write/both)</p>
    </div>
    <?php if (user_can($db, $userId, 'settings_roles', 'write')): ?>
    <a href="<?= BASE_URL ?>/settings/role_create.php" class="btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Role
    </a>
    <?php endif; ?>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-6 py-3 text-left font-semibold">Role</th>
                    <th class="px-6 py-3 text-left font-semibold">Description</th>
                    <th class="px-6 py-3 text-center font-semibold">Users</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($roles as $r): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-medium"><?= e($r['name']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= e($r['description'] ?: '—') ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950 dark:text-brand-300"><?= (int)$r['user_count'] ?></span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="table-actions">
                            <?php if (user_can($db, $userId, 'settings_roles', 'write')): ?>
                            <a href="<?= BASE_URL ?>/settings/role_edit.php?id=<?= (int)$r['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                            <span class="table-action-sep">|</span>
                            <form method="POST" class="inline" onsubmit="return confirm('Delete this role? Users will lose this role.');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="delete_role_id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn-action btn-action-delete">Delete</button>
                            </form>
                            <?php else: ?>
                            <span class="text-xs text-slate-400">No actions</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

