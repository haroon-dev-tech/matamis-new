<?php

/** @var string $pageTitle */
/** @var string $activeNav */
/** @var bool $requireAuth */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';
apply_security_headers();

if (!empty($requireAuth)) {
    require_login();
}

$db = getDB();
$currentUser = get_auth_user($db);
$flash = get_flash();
log_current_request($db, $currentUser);

if (!empty($requireAuth) && $currentUser) {
    ensure_user_profile_columns($db);
    ensure_rbac_seeded($db);
    $required = infer_permission_for_request($_SERVER['SCRIPT_NAME'] ?? '', $_SERVER['REQUEST_METHOD'] ?? 'GET');
    if ($required) {
        [$permKey, $mode] = $required;
        require_permission($db, current_user_id(), $permKey, $mode);
    }
}
$pageTitle = $pageTitle ?? APP_NAME;
$activeNav = $activeNav ?? '';
