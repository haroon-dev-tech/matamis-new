<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
if (is_logged_in()) {
    $db = getDB();
    redirect(get_default_landing_path($db, current_user_id()));
}
redirect('/login.php'); 
