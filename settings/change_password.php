<?php
$pageTitle = 'Change Password';
$activeNav = 'change-password';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $error = 'All password fields are required.';
        } elseif (strlen($newPassword) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New password and confirmation do not match.';
        } elseif ($currentPassword === $newPassword) {
            $error = 'New password must be different from your current password.';
        } else {
            $stmt = $db->prepare('SELECT id, password FROM users WHERE id = ? AND deleted_at IS NULL');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $error = 'Current password is incorrect.';
            } else {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $db->prepare('UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $stmt->execute([$hash, $userId]);

                log_activity($db, [
                    'user_id' => $userId,
                    'user_name' => $currentUser['full_name'] ?? null,
                    'user_email' => $currentUser['email'] ?? null,
                    'event_type' => 'auth',
                    'action' => 'password_changed',
                    'module' => 'authentication',
                    'description' => 'User changed their password.',
                ]);

                session_regenerate_id(true);
                flash('success', 'Your password has been updated successfully.');
                redirect('/settings/change_password.php');
            }
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">Update the password for your account.</p>
</div>

<div class="card p-8 max-w-lg">
    <?php if ($error): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
        <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4" autocomplete="off">
        <?= csrf_field() ?>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Current Password</label>
            <input type="password" name="current_password" class="input-field" required autofocus>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">New Password</label>
            <input type="password" name="new_password" class="input-field" placeholder="Min. 6 characters" required>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Confirm New Password</label>
            <input type="password" name="confirm_password" class="input-field" placeholder="Repeat new password" required>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">Update Password</button>
            <a href="<?= e(BASE_URL . get_default_landing_path($db, $userId)) ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
