<?php
$pageTitle = 'Activity Logs';
$activeNav = 'settings-logs';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
require_permission($db, $userId, 'settings_logs', 'read');

$q = trim((string) ($_GET['q'] ?? ''));
$eventType = trim((string) ($_GET['event_type'] ?? ''));
$action = trim((string) ($_GET['action'] ?? ''));
$moduleKey = trim((string) ($_GET['module_key'] ?? ''));
$requestMethod = trim((string) ($_GET['request_method'] ?? ''));
$dateFrom = trim((string) ($_GET['date_from'] ?? ''));
$dateTo = trim((string) ($_GET['date_to'] ?? ''));
$userFilter = trim((string) ($_GET['user'] ?? ''));
$ipFilter = trim((string) ($_GET['ip'] ?? ''));

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 50;
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($q !== '') {
    $where[] = '(al.description LIKE ? OR al.route_path LIKE ? OR al.user_name LIKE ? OR al.user_email LIKE ? OR al.metadata_json LIKE ?)';
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
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
if ($requestMethod !== '') {
    $where[] = 'al.request_method = ?';
    $params[] = strtoupper($requestMethod);
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
if ($ipFilter !== '') {
    $where[] = 'al.ip_address LIKE ?';
    $params[] = '%' . $ipFilter . '%';
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
$methods = $db->query('SELECT DISTINCT request_method FROM activity_logs ORDER BY request_method ASC')->fetchAll(PDO::FETCH_COLUMN);

$filterQuery = [
    'q' => $q ?: null,
    'event_type' => $eventType ?: null,
    'action' => $action ?: null,
    'module_key' => $moduleKey ?: null,
    'request_method' => $requestMethod ?: null,
    'date_from' => $dateFrom ?: null,
    'date_to' => $dateTo ?: null,
    'user' => $userFilter ?: null,
    'ip' => $ipFilter ?: null,
];

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Audit trail of user activity across authentication, navigation, and data actions
    </p>
</div>

<div class="card mb-6 p-6">
    <form method="GET" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-sm font-medium">Search</label>
                <input type="text" name="q" class="input-field" value="<?= e($q) ?>" placeholder="Description, route, user, email, metadata">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Event Type</label>
                <select name="event_type" class="input-field">
                    <option value="">All</option>
                    <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= e($type) ?>" <?= $eventType === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Action</label>
                <select name="action" class="input-field">
                    <option value="">All</option>
                    <?php foreach ($actions as $item): ?>
                    <option value="<?= e($item) ?>" <?= $action === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Module</label>
                <select name="module_key" class="input-field">
                    <option value="">All</option>
                    <?php foreach ($moduleKeys as $item): ?>
                    <option value="<?= e($item) ?>" <?= $moduleKey === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Method</label>
                <select name="request_method" class="input-field">
                    <option value="">All</option>
                    <?php foreach ($methods as $item): ?>
                    <option value="<?= e($item) ?>" <?= strtoupper($requestMethod) === strtoupper($item) ? 'selected' : '' ?>><?= e($item) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Date From</label>
                <input type="date" name="date_from" class="input-field" value="<?= e($dateFrom) ?>">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Date To</label>
                <input type="date" name="date_to" class="input-field" value="<?= e($dateTo) ?>">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">User (name/email)</label>
                <input type="text" name="user" class="input-field" value="<?= e($userFilter) ?>" placeholder="e.g. admin@mata.ae">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">IP Address</label>
                <input type="text" name="ip" class="input-field" value="<?= e($ipFilter) ?>" placeholder="e.g. 127.0.0.1">
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <a href="<?= BASE_URL ?>/settings/logs.php" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="mb-3 text-sm text-slate-500 dark:text-slate-400">
    Showing <?= count($logs) ?> of <?= $totalRows ?> log<?= $totalRows === 1 ? '' : 's' ?>.
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-4 py-3 text-left font-semibold">Date/Time</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                    <th class="px-4 py-3 text-left font-semibold">Event</th>
                    <th class="px-4 py-3 text-left font-semibold">Module</th>
                    <th class="px-4 py-3 text-left font-semibold">Route</th>
                    <th class="px-4 py-3 text-left font-semibold">IP</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-500">No activity logs found for selected filters.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr class="align-top hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-4 py-3 whitespace-nowrap text-slate-600"><?= e(date('d M Y H:i:s', strtotime($log['created_at']))) ?></td>
                    <td class="px-4 py-3">
                        <div class="font-medium"><?= e($log['user_name'] ?: 'System') ?></div>
                        <?php if (!empty($log['user_email'])): ?>
                        <div class="text-xs text-slate-500"><?= e($log['user_email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"><?= e($log['event_type']) ?></div>
                        <div class="mt-1 text-xs text-slate-500"><?= e($log['action']) ?> · <?= e($log['request_method']) ?></div>
                    </td>
                    <td class="px-4 py-3 text-slate-600"><?= e($log['module_key'] ?: '—') ?></td>
                    <td class="px-4 py-3">
                        <div class="font-mono text-xs text-slate-600 break-all"><?= e($log['route_path']) ?></div>
                        <?php if (!empty($log['metadata_json'])): ?>
                        <details class="mt-1">
                            <summary class="cursor-pointer text-xs text-brand-600">metadata</summary>
                            <pre class="mt-1 max-w-xl overflow-x-auto rounded bg-slate-100 p-2 text-[11px] leading-5 text-slate-700 dark:bg-slate-900 dark:text-slate-300"><?= e($log['metadata_json']) ?></pre>
                        </details>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap"><?= e($log['ip_address'] ?: '—') ?></td>
                    <td class="px-4 py-3 text-slate-600">
                        <?= e($log['description'] ?: '—') ?>
                        <?php if (!empty($log['user_agent'])): ?>
                        <div class="mt-1 text-xs text-slate-500 break-all"><?= e($log['user_agent']) ?></div>
                        <?php endif; ?>
                    </td>
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

