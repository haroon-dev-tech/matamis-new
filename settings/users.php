<?php
$pageTitle = 'Users';
$activeNav = 'settings-users';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_users', 'read');
ensure_user_profile_columns($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    require_permission($db, $userId, 'settings_users', 'write');
    if (!verify_csrf()) {
        flash('error', 'Invalid security token.');
        redirect('/settings/users.php');
    }

    $deleteId = (int) $_POST['delete_user_id'];
    if ($deleteId === (int) $userId) {
        flash('error', 'You cannot delete your own account.');
        redirect('/settings/users.php');
    }

    try {
        $stmt = $db->prepare('UPDATE users SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND deleted_at IS NULL');
        $stmt->execute([$deleteId]);
        $db->prepare('DELETE FROM user_roles WHERE user_id = ?')->execute([$deleteId]);
        flash('success', 'User deleted.');
    } catch (Exception $e) {
        flash('error', 'Failed to delete user.');
    }
    redirect('/settings/users.php');
}

$stmt = $db->query(
    'SELECT u.id, u.full_name, u.email, u.phone, u.designation, u.created_at,
            GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ", ") AS roles
     FROM users u
     LEFT JOIN user_roles ur ON ur.user_id = u.id
     LEFT JOIN roles r ON r.id = ur.role_id AND r.deleted_at IS NULL
     WHERE u.deleted_at IS NULL
     GROUP BY u.id
     ORDER BY u.created_at DESC'
);
$users = $stmt->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <p class="text-sm text-slate-500 dark:text-slate-400">Manage system users and assign roles</p>
    </div>
    <?php if (user_can($db, $userId, 'settings_users', 'write')): ?>
    <a href="<?= BASE_URL ?>/settings/user_create.php" class="btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create User
    </a>
    <?php endif; ?>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-6 py-3 text-left font-semibold">Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Email</th>
                    <th class="px-6 py-3 text-left font-semibold">Phone</th>
                    <th class="px-6 py-3 text-left font-semibold">Designation</th>
                    <th class="px-6 py-3 text-left font-semibold">Roles</th>
                    <th class="px-6 py-3 text-left font-semibold">Created</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-medium"><?= e($u['full_name']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= e($u['email']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= e($u['phone'] ?: '—') ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= e($u['designation'] ?: '—') ?></td>
                    <td class="px-6 py-4">
                        <span class="text-slate-500"><?= e($u['roles'] ?: '—') ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-500"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="table-actions">
                            <?php if (user_can($db, $userId, 'settings_users', 'write')): ?>
                            <a href="<?= BASE_URL ?>/settings/user_edit.php?id=<?= (int)$u['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                            <?php if ((int)$u['id'] !== (int)$userId): ?>
                            <span class="table-action-sep">|</span>
                            <form method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="delete_user_id" value="<?= (int)$u['id'] ?>">
                                <button type="submit" class="btn-action btn-action-delete">Delete</button>
                            </form>
                            <?php endif; ?>
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

