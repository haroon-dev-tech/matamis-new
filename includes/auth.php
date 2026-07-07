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
    $stmt = $db->prepare('SELECT id, full_name, email, password FROM users WHERE email = ? AND ' . not_deleted());
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return 'Invalid email or password.';
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    unset($_SESSION['rbac_perm_cache']);
    flash('success', 'Welcome back, ' . $user['full_name'] . '!');
    redirect('/dashboard.php');
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
    unset($_SESSION['rbac_perm_cache']);
    flash('success', 'Account created successfully. Welcome to Mata MIS!');
    redirect('/dashboard.php');
}

function handle_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    flash('success', 'You have been logged out.');
    redirect('/login.php');
}
