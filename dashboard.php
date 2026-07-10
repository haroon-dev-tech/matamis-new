<?php
$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
$requireAuth = true;
require __DIR__ . '/includes/bootstrap.php';

$userId = current_user_id();

$accessibleCompanies = get_accessible_companies($db, $userId, 'dashboard');
$companyIds = array_map('intval', array_column($accessibleCompanies, 'id'));
$companyCount = count($companyIds);

$branchCount = 0;
$branchesPerCompany = [];
$companyTrendLabels = [];
$companyTrendCounts = [];
$branchTrendCounts = [];

if (!empty($companyIds)) {
    $placeholders = implode(',', array_fill(0, count($companyIds), '?'));

    $stmt = $db->prepare(
        "SELECT COUNT(*) FROM branches
         WHERE company_id IN ($placeholders) AND deleted_at IS NULL"
    );
    $stmt->execute($companyIds);
    $branchCount = (int) $stmt->fetchColumn();

    $stmt = $db->prepare(
        "SELECT c.name, COUNT(b.id) AS branch_count
         FROM companies c
         LEFT JOIN branches b ON b.company_id = c.id AND b.deleted_at IS NULL
         WHERE c.id IN ($placeholders) AND c.deleted_at IS NULL
         GROUP BY c.id, c.name
         ORDER BY branch_count DESC, c.name ASC"
    );
    $stmt->execute($companyIds);
    $branchesPerCompany = $stmt->fetchAll();

    // Last 12 months registration trend
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
        $key = date('Y-m', strtotime("-{$i} months"));
        $months[$key] = [
            'label' => date('M Y', strtotime("-{$i} months")),
            'companies' => 0,
            'branches' => 0,
        ];
    }

    $stmt = $db->prepare(
        "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
         FROM companies
         WHERE id IN ($placeholders)
           AND deleted_at IS NULL
           AND created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
         GROUP BY ym"
    );
    $stmt->execute($companyIds);
    foreach ($stmt->fetchAll() as $row) {
        $ym = $row['ym'];
        if (isset($months[$ym])) {
            $months[$ym]['companies'] = (int) $row['total'];
        }
    }

    $stmt = $db->prepare(
        "SELECT DATE_FORMAT(b.created_at, '%Y-%m') AS ym, COUNT(*) AS total
         FROM branches b
         WHERE b.company_id IN ($placeholders)
           AND b.deleted_at IS NULL
           AND b.created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
         GROUP BY ym"
    );
    $stmt->execute($companyIds);
    foreach ($stmt->fetchAll() as $row) {
        $ym = $row['ym'];
        if (isset($months[$ym])) {
            $months[$ym]['branches'] = (int) $row['total'];
        }
    }

    foreach ($months as $month) {
        $companyTrendLabels[] = $month['label'];
        $companyTrendCounts[] = $month['companies'];
        $branchTrendCounts[] = $month['branches'];
    }
}

$stmt = $db->prepare(
    'SELECT COUNT(DISTINCT CONCAT(se.period_year, "-", se.period_month, "-", se.branch_id))
     FROM linked_bs_entries se
     INNER JOIN branches b ON b.id = se.branch_id
     INNER JOIN companies c ON c.id = b.company_id
     WHERE c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL'
);
$stmt->execute([$userId]);
$linkedBsCount = (int) $stmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT COUNT(DISTINCT CONCAT(se.period_year, "-", se.period_month, "-", se.branch_id))
     FROM linked_is_entries se
     INNER JOIN branches b ON b.id = se.branch_id
     INNER JOIN companies c ON c.id = b.company_id
     WHERE c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL'
);
$stmt->execute([$userId]);
$linkedIsCount = (int) $stmt->fetchColumn();

$recentCompanies = [];
if (!empty($companyIds)) {
    $placeholders = implode(',', array_fill(0, count($companyIds), '?'));
    $stmt = $db->prepare(
        "SELECT c.name, c.id, COUNT(b.id) as branch_count
         FROM companies c
         LEFT JOIN branches b ON b.company_id = c.id AND b.deleted_at IS NULL
         WHERE c.id IN ($placeholders) AND c.deleted_at IS NULL
         GROUP BY c.id
         ORDER BY c.created_at DESC
         LIMIT 5"
    );
    $stmt->execute($companyIds);
    $recentCompanies = $stmt->fetchAll();
}

$chartCompanyLabels = array_map(static fn($row) => $row['name'], $branchesPerCompany);
$chartBranchCounts = array_map(static fn($row) => (int) $row['branch_count'], $branchesPerCompany);

require __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <p class="text-slate-500 dark:text-slate-400">Welcome back, <span class="font-medium text-slate-900 dark:text-white"><?= e($currentUser['full_name']) ?></span></p>
</div>

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Companies</p>
                <p class="mt-1 text-3xl font-bold"><?= $companyCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-950 dark:text-brand-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Branches</p>
                <p class="mt-1 text-3xl font-bold"><?= $branchCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Linked BS Periods</p>
                <p class="mt-1 text-3xl font-bold"><?= $linkedBsCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Linked IS Periods</p>
                <p class="mt-1 text-3xl font-bold"><?= $linkedIsCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="mb-8 grid gap-6 lg:grid-cols-3">
    <div class="card lg:col-span-1">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Companies vs Branches</h2>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Current registered totals</p>
        </div>
        <div class="p-6">
            <div class="relative mx-auto h-64 max-w-xs">
                <canvas id="dashboard-overview-chart" aria-label="Companies versus branches chart"></canvas>
            </div>
            <?php if ($companyCount === 0 && $branchCount === 0): ?>
            <p class="mt-2 text-center text-sm text-slate-500">No companies or branches registered yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card lg:col-span-2">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Branches by Company</h2>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">How many branches each company has</p>
        </div>
        <div class="p-6">
            <div class="relative h-64">
                <canvas id="dashboard-branches-chart" aria-label="Branches by company chart"></canvas>
            </div>
            <?php if (empty($branchesPerCompany)): ?>
            <p class="mt-2 text-center text-sm text-slate-500">Register a company to see branch distribution.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mb-8 card">
    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h2 class="font-semibold">Registration Trend</h2>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Companies and branches registered over the last 12 months</p>
    </div>
    <div class="p-6">
        <div class="relative h-72">
            <canvas id="dashboard-trend-chart" aria-label="Company and branch registration trend chart"></canvas>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Quick Actions</h2>
        </div>
        <div class="grid gap-3 p-6 sm:grid-cols-2">
            <a href="<?= BASE_URL ?>/companies/create.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-600 dark:bg-brand-900 dark:text-brand-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="font-medium">Register Company</p>
                    <p class="text-xs text-slate-500">Add company & branches</p>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/linked-bs/entry.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-900 dark:text-violet-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="font-medium">Linked BS Entry</p>
                    <p class="text-xs text-slate-500">Balance sheet data</p>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/linked-is/entry.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <p class="font-medium">Linked IS Entry</p>
                    <p class="text-xs text-slate-500">Income statement data</p>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Recent Companies</h2>
            <a href="<?= BASE_URL ?>/companies/index.php" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400">View all</a>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-800">
            <?php if (empty($recentCompanies)): ?>
            <div class="p-6 text-center text-sm text-slate-500">
                No companies registered yet.
                <a href="<?= BASE_URL ?>/companies/create.php" class="text-brand-600 hover:underline">Register your first company</a>
            </div>
            <?php else: ?>
            <?php foreach ($recentCompanies as $co): ?>
            <div class="flex items-center justify-between px-6 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                <div>
                    <p class="font-medium"><?= e($co['name']) ?></p>
                    <p class="text-xs text-slate-500"><?= (int)$co['branch_count'] ?> branch<?= $co['branch_count'] != 1 ? 'es' : '' ?></p>
                </div>
                <div class="table-actions">
                    <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $co['id'] ?>" class="btn-action btn-action-view">View</a>
                    <span class="table-action-sep">|</span>
                    <a href="<?= BASE_URL ?>/companies/edit.php?id=<?= $co['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.12)' : 'rgba(148, 163, 184, 0.2)';

    const companyCount = <?= (int) $companyCount ?>;
    const branchCount = <?= (int) $branchCount ?>;
    const companyLabels = <?= json_encode(array_values($chartCompanyLabels), JSON_UNESCAPED_UNICODE) ?>;
    const branchCounts = <?= json_encode(array_values($chartBranchCounts)) ?>;
    const trendLabels = <?= json_encode(array_values($companyTrendLabels), JSON_UNESCAPED_UNICODE) ?>;
    const companyTrend = <?= json_encode(array_values($companyTrendCounts)) ?>;
    const branchTrend = <?= json_encode(array_values($branchTrendCounts)) ?>;

    const overviewEl = document.getElementById('dashboard-overview-chart');
    if (overviewEl) {
        new Chart(overviewEl, {
            type: 'doughnut',
            data: {
                labels: ['Companies', 'Branches'],
                datasets: [{
                    data: [companyCount, branchCount],
                    backgroundColor: ['#0c8ce9', '#10b981'],
                    borderColor: isDark ? '#0f172a' : '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor, boxWidth: 12, padding: 16 },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.label + ': ' + Number(ctx.raw || 0).toLocaleString();
                            },
                        },
                    },
                },
            },
        });
    }

    const branchesEl = document.getElementById('dashboard-branches-chart');
    if (branchesEl) {
        new Chart(branchesEl, {
            type: 'bar',
            data: {
                labels: companyLabels.length ? companyLabels : ['No companies'],
                datasets: [{
                    label: 'Branches',
                    data: companyLabels.length ? branchCounts : [0],
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 8,
                    maxBarThickness: 48,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return 'Branches: ' + Number(ctx.raw || 0).toLocaleString();
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            maxRotation: 45,
                            minRotation: 0,
                            callback: function (value) {
                                const label = this.getLabelForValue(value);
                                return label.length > 18 ? label.slice(0, 16) + '…' : label;
                            },
                        },
                        grid: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            precision: 0,
                            stepSize: 1,
                        },
                        grid: { color: gridColor },
                    },
                },
            },
        });
    }

    const trendEl = document.getElementById('dashboard-trend-chart');
    if (trendEl) {
        new Chart(trendEl, {
            type: 'line',
            data: {
                labels: trendLabels.length ? trendLabels : ['No data'],
                datasets: [
                    {
                        label: 'Companies',
                        data: trendLabels.length ? companyTrend : [0],
                        borderColor: '#0c8ce9',
                        backgroundColor: 'rgba(12, 140, 233, 0.15)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Branches',
                        data: trendLabels.length ? branchTrend : [0],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor, boxWidth: 12, padding: 16 },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ': ' + Number(ctx.raw || 0).toLocaleString();
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { color: textColor, maxRotation: 0 },
                        grid: { color: gridColor },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            precision: 0,
                            stepSize: 1,
                        },
                        grid: { color: gridColor },
                    },
                },
            },
        });
    }
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
