<?php
$pageTitle = 'Create User';
$activeNav = 'settings-users';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_users', 'write');

$error = null;

$rolesStmt = $db->query('SELECT id, name FROM roles WHERE deleted_at IS NULL ORDER BY name ASC');
$roles = $rolesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleIds = isset($_POST['role_ids']) && is_array($_POST['role_ids']) ? array_map('intval', $_POST['role_ids']) : [];

        if ($fullName === '' || $email === '' || $password === '') {
            $error = 'Name, email and password are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND deleted_at IS NULL');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'A user with this email already exists.';
            } else {
                try {
                    $db->beginTransaction();
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
                    $stmt->execute([$fullName, $email, $hash]);
                    $newUserId = (int) $db->lastInsertId();

                    if (!empty($roleIds)) {
                        $ins = $db->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
                        foreach ($roleIds as $rid) {
                            $ins->execute([$newUserId, $rid]);
                        }
                    }

                    $db->commit();
                    flash('success', 'User created successfully.');
                    redirect('/settings/users.php');
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Failed to create user.';
                }
            }
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">Create a new system user and assign roles.</p>
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
            <input type="text" name="full_name" class="input-field" value="<?= e($_POST['full_name'] ?? '') ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Email</label>
            <input type="email" name="email" class="input-field" value="<?= e($_POST['email'] ?? '') ?>" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Password</label>
            <input type="password" name="password" class="input-field" placeholder="Min. 6 characters" required>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Roles</label>
            <?php if (empty($roles)): ?>
                <p class="text-sm text-slate-500">No roles found. Create a role first.</p>
            <?php else: ?>
                <div class="grid gap-2 sm:grid-cols-2">
                    <?php foreach ($roles as $r): ?>
                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 p-3 text-sm dark:border-slate-800">
                        <input type="checkbox" name="role_ids[]" value="<?= (int)$r['id'] ?>">
                        <span class="font-medium"><?= e($r['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">Create User</button>
            <a href="<?= BASE_URL ?>/settings/users.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

