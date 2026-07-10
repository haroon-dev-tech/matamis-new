<?php
$pageTitle = 'Activity Logs';
$activeNav = 'settings-logs';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_logs', 'read');

function activity_module_label(?string $moduleKey): string
{
    $map = [
        'dashboard' => 'Dashboard',
        'companies' => 'Companies',
        'observations' => 'Observations',
        'linked_is' => 'Linked Income Statement',
        'linked_bs' => 'Linked Balance Sheet',
        'somfp' => 'Monthly Financial Position (SOMFP)',
        'somci' => 'Monthly Comprehensive Income (SOMCI)',
        'sofp' => 'Overall Financial Position (SOFP)',
        'soci' => 'Overall Comprehensive Income (SOCI)',
        'glance' => 'Glance Picture',
        'settings_users' => 'User Settings',
        'settings_roles' => 'Role Settings',
        'settings_logs' => 'Activity Logs',
        'authentication' => 'Login & Security',
    ];

    if (!$moduleKey) {
        return 'General';
    }

    return $map[$moduleKey] ?? ucwords(str_replace('_', ' ', $moduleKey));
}

function activity_event_label(string $eventType): string
{
    $map = [
        'auth' => 'Login & Security',
        'view' => 'Viewed a page',
        'write' => 'Saved or changed data',
        'create' => 'Created a record',
        'update' => 'Updated a record',
        'delete' => 'Deleted a record',
        'access_denied' => 'Access blocked',
    ];

    return $map[$eventType] ?? ucwords(str_replace('_', ' ', $eventType));
}

function activity_action_label(string $action): string
{
    $map = [
        'login_success' => 'Signed in successfully',
        'login_failed' => 'Failed sign-in attempt',
        'logout' => 'Signed out',
        'password_changed' => 'Changed password',
        'view_page' => 'Opened a page',
        'list_page' => 'Opened a list page',
        'form_page' => 'Opened a form',
        'view_record' => 'Viewed a record',
        'create_record' => 'Created a record',
        'edit_record' => 'Edited a record',
        'update' => 'Saved changes',
        'delete_record' => 'Deleted a record',
        'deny' => 'Was blocked from a page',
    ];

    return $map[$action] ?? ucwords(str_replace('_', ' ', $action));
}

function activity_page_label(string $routePath): string
{
    $path = strtolower($routePath);
    $map = [
        '/login.php' => 'Sign In page',
        '/logout.php' => 'Sign Out',
        '/register.php' => 'Registration page',
        '/dashboard.php' => 'Dashboard',
        '/landing.php' => 'Landing page',
        '/forbidden.php' => 'Access Denied page',
        '/companies/index.php' => 'Companies list',
        '/companies/create.php' => 'Create Company',
        '/companies/edit.php' => 'Edit Company',
        '/companies/view.php' => 'Company details',
        '/observations/index.php' => 'Observations list',
        '/observations/create.php' => 'Create Observation',
        '/observations/edit.php' => 'Edit Observation',
        '/observations/view.php' => 'Observation details',
        '/linked-is/index.php' => 'Linked IS home',
        '/linked-is/entry.php' => 'Linked IS data entry',
        '/linked-is/structure.php' => 'Linked IS structure setup',
        '/linked-bs/index.php' => 'Linked BS home',
        '/linked-bs/entry.php' => 'Linked BS data entry',
        '/linked-bs/structure.php' => 'Linked BS structure setup',
        '/somfp/index.php' => 'SOMFP report',
        '/somci/index.php' => 'SOMCI report',
        '/sofp/index.php' => 'SOFP report',
        '/soci/index.php' => 'SOCI report',
        '/glance/index.php' => 'Glance Picture',
        '/settings/users.php' => 'Users settings',
        '/settings/user_create.php' => 'Create User',
        '/settings/user_edit.php' => 'Edit User',
        '/settings/roles.php' => 'Roles settings',
        '/settings/role_create.php' => 'Create Role',
        '/settings/role_edit.php' => 'Edit Role',
        '/settings/logs.php' => 'Activity Logs',
        '/settings/change_password.php' => 'Change Password',
    ];

    foreach ($map as $needle => $label) {
        if (strpos($path, $needle) !== false) {
            return $label;
        }
    }

    $base = basename(parse_url($routePath, PHP_URL_PATH) ?: $routePath);
    return $base ? ucwords(str_replace(['_', '-', '.php'], [' ', ' ', ''], $base)) : 'Unknown page';
}

function activity_summary(array $log): string
{
    $who = trim((string) ($log['user_name'] ?: ''));
    if ($who === '') {
        $who = !empty($log['user_email']) ? (string) $log['user_email'] : 'Someone';
    }

    $action = (string) ($log['action'] ?? '');
    $event = (string) ($log['event_type'] ?? '');
    $page = activity_page_label((string) ($log['route_path'] ?? ''));
    $section = activity_module_label($log['module_key'] ?? null);

    if ($action === 'login_success') {
        return $who . ' signed in successfully.';
    }
    if ($action === 'login_failed') {
        $email = $log['user_email'] ?: 'an unknown email';
        return 'Failed sign-in attempt using ' . $email . '.';
    }
    if ($action === 'logout') {
        return $who . ' signed out.';
    }
    if ($action === 'password_changed') {
        return $who . ' changed their password.';
    }
    if ($event === 'access_denied' || $action === 'deny') {
        return $who . ' tried to open ' . $page . ' but did not have permission.';
    }
    if (in_array($action, ['create_record', 'create'], true) || $event === 'create') {
        return $who . ' created a record in ' . $section . ' (' . $page . ').';
    }
    if (in_array($action, ['edit_record', 'update'], true) || $event === 'update' || $event === 'write') {
        return $who . ' saved changes in ' . $section . ' (' . $page . ').';
    }
    if (in_array($action, ['delete_record', 'delete'], true) || $event === 'delete') {
        return $who . ' deleted a record in ' . $section . ' (' . $page . ').';
    }
    if ($action === 'view_record') {
        return $who . ' viewed a record in ' . $section . ' (' . $page . ').';
    }
    if ($action === 'list_page') {
        return $who . ' opened the ' . $page . '.';
    }
    if ($action === 'form_page') {
        return $who . ' opened the ' . $page . '.';
    }

    if (!empty($log['description']) && stripos((string) $log['description'], 'HTTP request') === false) {
        return (string) $log['description'];
    }

    return $who . ' opened ' . $page . '.';
}

function activity_badge_class(string $eventType, string $action): string
{
    if ($eventType === 'access_denied' || $action === 'login_failed' || $action === 'deny') {
        return 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300';
    }
    if ($eventType === 'auth' && $action === 'login_success') {
        return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300';
    }
    if (in_array($eventType, ['create', 'write', 'update'], true) || in_array($action, ['create_record', 'edit_record', 'update'], true)) {
        return 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300';
    }
    if ($eventType === 'delete' || $action === 'delete_record') {
        return 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300';
    }
    return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
}

$q = trim((string) ($_GET['q'] ?? ''));
$eventType = trim((string) ($_GET['event_type'] ?? ''));
$action = trim((string) ($_GET['action'] ?? ''));
$moduleKey = trim((string) ($_GET['module_key'] ?? ''));
$dateFrom = trim((string) ($_GET['date_from'] ?? ''));
$dateTo = trim((string) ($_GET['date_to'] ?? ''));
$userFilter = trim((string) ($_GET['user'] ?? ''));

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 50;
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($q !== '') {
    $where[] = '(al.description LIKE ? OR al.route_path LIKE ? OR al.user_name LIKE ? OR al.user_email LIKE ? OR al.action LIKE ? OR al.event_type LIKE ? OR al.module_key LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like, $like, $like, $like, $like);
}
if ($eventType !== '') {
    $where[] = 'al.event_type = ?';
    $params[] = $eventType;
}
if ($action !== '') {
    $where[] = 'al.action = ?';
    $params[] = $action;
}
if ($moduleKey !== '') {
    $where[] = 'al.module_key = ?';
    $params[] = $moduleKey;
}
if ($dateFrom !== '') {
    $where[] = 'DATE(al.created_at) >= ?';
    $params[] = $dateFrom;
}
if ($dateTo !== '') {
    $where[] = 'DATE(al.created_at) <= ?';
    $params[] = $dateTo;
}
if ($userFilter !== '') {
    $where[] = '(al.user_name LIKE ? OR al.user_email LIKE ?)';
    $userLike = '%' . $userFilter . '%';
    $params[] = $userLike;
    $params[] = $userLike;
}

$whereSql = $where ? (' WHERE ' . implode(' AND ', $where)) : '';

$countSql = 'SELECT COUNT(*) FROM activity_logs al' . $whereSql;
$countStmt = $db->prepare($countSql);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$sql = 'SELECT al.*
        FROM activity_logs al' . $whereSql . '
        ORDER BY al.created_at DESC, al.id DESC
        LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;
$stmt = $db->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

$eventTypes = $db->query('SELECT DISTINCT event_type FROM activity_logs ORDER BY event_type ASC')->fetchAll(PDO::FETCH_COLUMN);
$actions = $db->query('SELECT DISTINCT action FROM activity_logs ORDER BY action ASC')->fetchAll(PDO::FETCH_COLUMN);
$moduleKeys = $db->query('SELECT DISTINCT module_key FROM activity_logs WHERE module_key IS NOT NULL AND module_key <> "" ORDER BY module_key ASC')->fetchAll(PDO::FETCH_COLUMN);

$filterQuery = [
    'q' => $q ?: null,
    'event_type' => $eventType ?: null,
    'action' => $action ?: null,
    'module_key' => $moduleKey ?: null,
    'date_from' => $dateFrom ?: null,
    'date_to' => $dateTo ?: null,
    'user' => $userFilter ?: null,
];

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        A simple history of who signed in, what pages they opened, and what records they changed.
    </p>
</div>

<div class="card mb-6 p-6">
    <form method="GET" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-sm font-medium">Search</label>
                <input type="text" name="q" class="input-field" value="<?= e($q) ?>" placeholder="Search by person, page, or activity">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Activity Type</label>
                <select name="event_type" class="input-field">
                    <option value="">All activities</option>
                    <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= e($type) ?>" <?= $eventType === $type ? 'selected' : '' ?>><?= e(activity_event_label($type)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">What Happened</label>
                <select name="action" class="input-field">
                    <option value="">All actions</option>
                    <?php foreach ($actions as $item): ?>
                    <option value="<?= e($item) ?>" <?= $action === $item ? 'selected' : '' ?>><?= e(activity_action_label($item)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Section</label>
                <select name="module_key" class="input-field">
                    <option value="">All sections</option>
                    <?php foreach ($moduleKeys as $item): ?>
                    <option value="<?= e($item) ?>" <?= $moduleKey === $item ? 'selected' : '' ?>><?= e(activity_module_label($item)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">From Date</label>
                <input type="date" name="date_from" class="input-field" value="<?= e($dateFrom) ?>">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">To Date</label>
                <input type="date" name="date_to" class="input-field" value="<?= e($dateTo) ?>">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Person</label>
                <input type="text" name="user" class="input-field" value="<?= e($userFilter) ?>" placeholder="Name or email">
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <a href="<?= BASE_URL ?>/settings/logs.php" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="mb-3 text-sm text-slate-500 dark:text-slate-400">
    Showing <?= count($logs) ?> of <?= $totalRows ?> activit<?= $totalRows === 1 ? 'y' : 'ies' ?>.
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-4 py-3 text-left font-semibold">When</th>
                    <th class="px-4 py-3 text-left font-semibold">Who</th>
                    <th class="px-4 py-3 text-left font-semibold">What Happened</th>
                    <th class="px-4 py-3 text-left font-semibold">Section</th>
                    <th class="px-4 py-3 text-left font-semibold">Page</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">No activity found for the selected filters.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <?php
                $summary = activity_summary($log);
                $eventLabel = activity_event_label((string) $log['event_type']);
                $actionLabel = activity_action_label((string) $log['action']);
                $sectionLabel = activity_module_label($log['module_key'] ?? null);
                $pageLabel = activity_page_label((string) $log['route_path']);
                $badgeClass = activity_badge_class((string) $log['event_type'], (string) $log['action']);
                ?>
                <tr class="align-top hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                        <div class="font-medium"><?= e(date('d M Y', strtotime($log['created_at']))) ?></div>
                        <div class="text-xs text-slate-500"><?= e(date('h:i A', strtotime($log['created_at']))) ?></div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium"><?= e($log['user_name'] ?: 'Unknown user') ?></div>
                        <?php if (!empty($log['user_email'])): ?>
                        <div class="text-xs text-slate-500"><?= e($log['user_email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium <?= e($badgeClass) ?>">
                            <?= e($actionLabel) ?>
                        </div>
                        <div class="mt-2 text-slate-700 dark:text-slate-200"><?= e($summary) ?></div>
                        <div class="mt-1 text-xs text-slate-500"><?= e($eventLabel) ?></div>
                        <?php if (!empty($log['ip_address'])): ?>
                        <details class="mt-2">
                            <summary class="cursor-pointer text-xs text-brand-600">More details</summary>
                            <div class="mt-2 space-y-1 rounded-lg bg-slate-50 p-3 text-xs text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                                <p><span class="font-medium">Device location (IP):</span> <?= e($log['ip_address']) ?></p>
                                <?php if (!empty($log['user_agent'])): ?>
                                <p><span class="font-medium">Browser/device:</span> <?= e($log['user_agent']) ?></p>
                                <?php endif; ?>
                            </div>
                        </details>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-600"><?= e($sectionLabel) ?></td>
                    <td class="px-4 py-3 text-slate-600"><?= e($pageLabel) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm">
    <p class="text-slate-500">Page <?= $page ?> of <?= $totalPages ?></p>
    <div class="flex gap-2">
        <?php
        $prevPage = max(1, $page - 1);
        $nextPage = min($totalPages, $page + 1);
        $prevQuery = report_filter_query(array_merge($filterQuery, ['page' => $prevPage]));
        $nextQuery = report_filter_query(array_merge($filterQuery, ['page' => $nextPage]));
        ?>
        <a href="<?= BASE_URL ?>/settings/logs.php<?= $prevQuery ?>" class="btn-secondary <?= $page <= 1 ? 'pointer-events-none opacity-50' : '' ?>">Previous</a>
        <a href="<?= BASE_URL ?>/settings/logs.php<?= $nextQuery ?>" class="btn-secondary <?= $page >= $totalPages ? 'pointer-events-none opacity-50' : '' ?>">Next</a>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
