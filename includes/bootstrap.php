<?php

/** @var string $pageTitle */
/** @var string $activeNav */
/** @var bool $requireAuth */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!empty($requireAuth)) {
    require_login();
}

$db = getDB();
$currentUser = get_auth_user($db);
$flash = get_flash();
$pageTitle = $pageTitle ?? APP_NAME;
$activeNav = $activeNav ?? '';
