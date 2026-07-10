<?php
$pageTitle = 'Edit User';
$activeNav = 'settings-users';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$actorId = current_user_id();
require_permission($db, $actorId, 'settings_users', 'write');
ensure_user_profile_columns($db);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    flash('error', 'User not found.');
    redirect('/settings/users.php');
}

$stmt = $db->prepare('SELECT id, full_name, email, phone, designation, created_at FROM users WHERE id = ? AND deleted_at IS NULL');
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    flash('error', 'User not found.');
    redirect('/settings/users.php');
}

$rolesStmt = $db->query('SELECT id, name FROM roles WHERE deleted_at IS NULL ORDER BY name ASC');
$roles = $rolesStmt->fetchAll();

$stmt = $db->prepare('SELECT role_id FROM user_roles WHERE user_id = ?');
$stmt->execute([$id]);
$currentRoleIds = array_map('intval', array_column($stmt->fetchAll(), 'role_id'));

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleIds = isset($_POST['role_ids']) && is_array($_POST['role_ids']) ? array_map('intval', $_POST['role_ids']) : [];

        if ($fullName === '' || $email === '' || $phone === '' || $designation === '') {
            $error = 'Name, email, phone and designation are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (!preg_match('/^[0-9+\-\s()]{7,30}$/', $phone)) {
            $error = 'Please enter a valid phone number.';
        } else {
            $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL');
            $stmt->execute([$email, $id]);
            if ($stmt->fetch()) {
                $error = 'Another user already uses this email.';
            } else {
                try {
                    $db->beginTransaction();

                    if ($password !== '') {
                        if (strlen($password) < 6) {
                            throw new RuntimeException('Password must be at least 6 characters.');
                        }
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare('UPDATE users SET full_name = ?, email = ?, phone = ?, designation = ?, password = ? WHERE id = ?');
                        $stmt->execute([$fullName, $email, $phone, $designation, $hash, $id]);
                    } else {
                        $stmt = $db->prepare('UPDATE users SET full_name = ?, email = ?, phone = ?, designation = ? WHERE id = ?');
                        $stmt->execute([$fullName, $email, $phone, $designation, $id]);
                    }

                    $db->prepare('DELETE FROM user_roles WHERE user_id = ?')->execute([$id]);
                    if (!empty($roleIds)) {
                        $ins = $db->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
                        foreach ($roleIds as $rid) {
                            $ins->execute([$id, $rid]);
                        }
                    }

                    $db->commit();
                    unset($_SESSION['rbac_perm_cache']);
                    flash('success', 'User updated.');
                    redirect('/settings/users.php');
                } catch (RuntimeException $e) {
                    $db->rollBack();
                    $error = $e->getMessage();
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Failed to update user.';
                }
            }
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">Update user profile and role assignments.</p>
</div>

<div class="card p-8 max-w-2xl">
    <?php if ($error): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
        <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Full Name</label>
            <input type="text" name="full_name" class="input-field" value="<?= e($_POST['full_name'] ?? $user['full_name']) ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Email</label>
            <input type="email" name="email" class="input-field" value="<?= e($_POST['email'] ?? $user['email']) ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Phone</label>
            <input type="tel" name="phone" class="input-field" placeholder="+971 50 123 4567" value="<?= e($_POST['phone'] ?? ($user['phone'] ?? '')) ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Designation</label>
            <input type="text" name="designation" class="input-field" placeholder="e.g. Finance Manager" value="<?= e($_POST['designation'] ?? ($user['designation'] ?? '')) ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">New Password (optional)</label>
            <input type="password" name="password" class="input-field" placeholder="Leave blank to keep existing password">
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Roles</label>
            <?php if (empty($roles)): ?>
                <p class="text-sm text-slate-500">No roles found.</p>
            <?php else: ?>
                <div class="grid gap-2 sm:grid-cols-2">
                    <?php
                        $selected = isset($_POST['role_ids']) && is_array($_POST['role_ids'])
                            ? array_map('intval', $_POST['role_ids'])
                            : $currentRoleIds;
                    ?>
                    <?php foreach ($roles as $r): ?>
                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 p-3 text-sm dark:border-slate-800">
                        <input type="checkbox" name="role_ids[]" value="<?= (int)$r['id'] ?>" <?= in_array((int)$r['id'], $selected, true) ? 'checked' : '' ?>>
                        <span class="font-medium"><?= e($r['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/settings/users.php" class="btn-secondary">Back</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

