<?php

if (!defined('BASE_URL')) {
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $documentRoot = $documentRoot !== '' ? str_replace('\\', '/', realpath($documentRoot)) : '';
    $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)) ?: '');

    if ($documentRoot && $projectRoot && strpos($projectRoot, $documentRoot) === 0) {
        $base = substr($projectRoot, strlen($documentRoot));
        $base = '/' . trim(str_replace('\\', '/', $base), '/');
        define('BASE_URL', $base === '/' ? '' : $base);
    } else {
        define('BASE_URL', '/matamis');
    }
}

define('APP_NAME', 'Mata Consultancy MIS');
define('APP_SHORT', 'Mata MIS');
define('APP_LOGO_MARK', '/assets/images/mata-logo-mark.svg');
define('APP_LOGO_FULL', '/assets/images/mata-logo.svg');

$appCssPath = dirname(__DIR__) . '/assets/css/app.css';
define('APP_ASSET_VERSION', file_exists($appCssPath) ? (string) filemtime($appCssPath) : '1');

define('MONTHS', [
    1  => 'January', 2  => 'February', 3  => 'March',
    4  => 'April',   5  => 'May',      6  => 'June',
    7  => 'July',    8  => 'August',   9  => 'September',
    10 => 'October', 11 => 'November', 12 => 'December',
]);

define('OBSERVATION_STATUSES', ['Open', 'In Progress', 'Completed']);
