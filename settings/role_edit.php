<?php
$pageTitle = 'Edit Role';
$activeNav = 'settings-roles';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_roles', 'write');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    flash('error', 'Role not found.');
    redirect('/settings/roles.php');
}

$stmt = $db->prepare('SELECT id, name, description FROM roles WHERE id = ? AND deleted_at IS NULL');
$stmt->execute([$id]);
$role = $stmt->fetch();
if (!$role) {
    flash('error', 'Role not found.');
    redirect('/settings/roles.php');
}

$permissions = $db->query('SELECT id, perm_key, label, description FROM permissions ORDER BY label ASC')->fetchAll();

$stmt = $db->prepare(
    'SELECT p.perm_key, rp.can_read, rp.can_write
     FROM role_permissions rp
     INNER JOIN permissions p ON p.id = rp.permission_id
     WHERE rp.role_id = ?'
);
$stmt->execute([$id]);
$current = [];
foreach ($stmt->fetchAll() as $row) {
    $current[$row['perm_key']] = [
        'read' => (bool) $row['can_read'],
        'write' => (bool) $row['can_write'],
    ];
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $access = isset($_POST['access']) && is_array($_POST['access']) ? $_POST['access'] : [];

        if ($name === '') {
            $error = 'Role name is required.';
        } else {
            try {
                $db->beginTransaction();
                $stmt = $db->prepare('UPDATE roles SET name = ?, description = ? WHERE id = ?');
                $stmt->execute([$name, $description !== '' ? $description : null, $id]);

                $db->prepare('DELETE FROM role_permissions WHERE role_id = ?')->execute([$id]);
                $ins = $db->prepare('INSERT INTO role_permissions (role_id, permission_id, can_read, can_write) VALUES (?, ?, ?, ?)');
                foreach ($permissions as $p) {
                    $key = $p['perm_key'];
                    $val = $access[$key] ?? 'none';
                    $canRead = ($val === 'read' || $val === 'both') ? 1 : 0;
                    $canWrite = ($val === 'write' || $val === 'both') ? 1 : 0;
                    if ($canRead || $canWrite) {
                        $ins->execute([$id, (int) $p['id'], $canRead, $canWrite]);
                    }
                }

                $db->commit();
                unset($_SESSION['rbac_perm_cache']);
                flash('success', 'Role updated.');
                redirect('/settings/roles.php');
            } catch (Exception $e) {
                $db->rollBack();
                $error = 'Failed to update role.';
            }
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">Update role information and permissions.</p>
</div>

<div class="card p-8 max-w-4xl">
    <?php if ($error): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
        <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium">Role Name</label>
                <input type="text" name="name" class="input-field" value="<?= e($_POST['name'] ?? $role['name']) ?>" required>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Description (optional)</label>
                <input type="text" name="description" class="input-field" value="<?= e($_POST['description'] ?? ($role['description'] ?? '')) ?>">
            </div>
        </div>

        <div>
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-sm font-semibold">Permissions</h2>
                <p class="text-xs text-slate-500">Read controls tabs visibility; Write controls create/edit/delete/POST.</p>
            </div>

            <div class="card overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                                <th class="px-4 py-3 text-left font-semibold">Module</th>
                                <th class="px-4 py-3 text-left font-semibold">Access</th>
                                <th class="px-4 py-3 text-left font-semibold">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <?php foreach ($permissions as $p): ?>
                            <?php
                                $key = $p['perm_key'];
                                $default = 'none';
                                if (!empty($current[$key]['read']) && !empty($current[$key]['write'])) {
                                    $default = 'both';
                                } elseif (!empty($current[$key]['write'])) {
                                    $default = 'write';
                                } elseif (!empty($current[$key]['read'])) {
                                    $default = 'read';
                                }
                                $selected = $_POST['access'][$key] ?? $default;
                            ?>
                            <tr>
                                <td class="px-4 py-3 font-medium"><?= e($p['label']) ?></td>
                                <td class="px-4 py-3">
                                    <select name="access[<?= e($key) ?>]" class="input-field">
                                        <option value="none" <?= $selected === 'none' ? 'selected' : '' ?>>None</option>
                                        <option value="read" <?= $selected === 'read' ? 'selected' : '' ?>>Read</option>
                                        <option value="write" <?= $selected === 'write' ? 'selected' : '' ?>>Write</option>
                                        <option value="both" <?= $selected === 'both' ? 'selected' : '' ?>>Both</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-slate-500"><?= e($p['description'] ?: '—') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/settings/roles.php" class="btn-secondary">Back</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

