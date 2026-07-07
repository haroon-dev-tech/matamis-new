<?php

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

function handle_login(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }
    if (!verify_csrf()) {
        return 'Invalid security token. Please try again.';
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        return 'Email and password are required.';
    }

    $db = getDB();
    if (is_login_rate_limited($db, $email, request_ip_address())) {
        return 'Too many failed login attempts. Please wait 15 minutes and try again.';
    }

    $stmt = $db->prepare('SELECT id, full_name, email, password FROM users WHERE email = ? AND ' . not_deleted());
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        log_activity($db, [
            'user_id' => null,
            'user_name' => null,
            'user_email' => $email !== '' ? $email : null,
            'event_type' => 'auth',
            'action' => 'login_failed',
            'module' => 'authentication',
            'description' => 'Failed login attempt.',
        ]);
        return 'Invalid email or password.';
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    session_regenerate_id(true);
    unset($_SESSION['rbac_perm_cache']);
    log_activity($db, [
        'user_id' => (int) $user['id'],
        'user_name' => $user['full_name'],
        'user_email' => $user['email'],
        'event_type' => 'auth',
        'action' => 'login_success',
        'module' => 'authentication',
        'description' => 'User logged in successfully.',
    ]);
    flash('success', 'Welcome back, ' . $user['full_name'] . '!');
    redirect(get_default_landing_path($db, (int) $user['id']));
}

function handle_register(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }
    if (!verify_csrf()) {
        return 'Invalid security token. Please try again.';
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    if ($fullName === '' || $email === '' || $password === '') {
        return 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address.';
    }
    if (strlen($password) < 6) {
        return 'Password must be at least 6 characters.';
    }
    if ($password !== $confirm) {
        return 'Passwords do not match.';
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND ' . not_deleted());
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return 'An account with this email already exists.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$fullName, $email, $hash]);

    $_SESSION['user_id'] = (int) $db->lastInsertId();
    $_SESSION['user_name'] = $fullName;
    $newUserId = (int) $_SESSION['user_id'];
    unset($_SESSION['rbac_perm_cache']);
    flash('success', 'Account created successfully. Welcome to Mata MIS!');
    redirect(get_default_landing_path($db, $newUserId));
}

function handle_logout(): void
{
    $db = getDB();
    $logoutUser = null;
    if (!empty($_SESSION['user_id'])) {
        $stmt = $db->prepare('SELECT id, full_name, email FROM users WHERE id = ?');
        $stmt->execute([(int) $_SESSION['user_id']]);
        $logoutUser = $stmt->fetch() ?: null;
    }

    log_activity($db, [
        'user_id' => $logoutUser['id'] ?? null,
        'user_name' => $logoutUser['full_name'] ?? ($_SESSION['user_name'] ?? null),
        'user_email' => $logoutUser['email'] ?? null,
        'event_type' => 'auth',
        'action' => 'logout',
        'module' => 'authentication',
        'description' => 'User logged out.',
    ]);

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    flash('success', 'You have been logged out.');
    redirect('/login.php');
}
