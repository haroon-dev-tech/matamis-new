<?php if (empty($ciPeriodRows)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No Linked IS data found for the selected filters.</p>
    <a href="<?= BASE_URL ?>/linked-is/entry.php?company_id=<?= $selectedCompanyId ?>" class="btn-primary mt-4">Enter Linked IS Data</a>
</div>
<?php else: ?>

<div class="card mb-6 overflow-hidden" id="glance-ci-panel">
    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <div>
            <h2 class="font-semibold">Linked Income Statement</h2>
            <p class="mt-1 text-sm text-slate-500">Head totals from Linked IS structure by period (newest to oldest)</p>
        </div>
        <div class="chart-toolbar">
            <div class="chart-type-switcher" role="tablist" aria-label="Chart type">
                <button type="button" class="chart-type-btn" data-chart-type="bar" role="tab" aria-selected="false">Bar</button>
                <button type="button" class="chart-type-btn active" data-chart-type="line" role="tab" aria-selected="true">Line</button>
                <button type="button" class="chart-type-btn" data-chart-type="area" role="tab" aria-selected="false">Area</button>
                <button type="button" class="chart-type-btn" data-chart-type="stacked" role="tab" aria-selected="false">Stacked</button>
            </div>
            <button type="button" id="glance-ci-chart-download" class="chart-download-btn" title="Download chart as PNG">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                Download PNG
            </button>
        </div>
    </div>

    <div class="glance-combo glance-combo-ci overflow-x-auto" id="glance-ci-combo-export">
        <div class="glance-combo-chart-row">
            <div aria-hidden="true"></div>
            <div class="glance-combo-chart-wrap glance-combo-chart-wrap-tall">
                <canvas id="glance-ci-chart"></canvas>
            </div>
        </div>
        <table class="glance-combo-table">
            <tbody>
                <?php foreach ($ciHeadSeries as $series): ?>
                <tr>
                    <td class="label-col">
                        <span class="glance-combo-legend" aria-hidden="true" style="background: <?= e($series['color']) ?>;"></span><?= e(strtoupper($series['label'])) ?>:
                    </td>
                    <?php foreach ($ciPeriodRows as $row): ?>
                    <td><?= format_money($row['totals']['head_totals'][$series['head_id']] ?? 0) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="label-col">
                        <span class="glance-combo-legend profit-loss" aria-hidden="true"></span>NET PROFIT/LOSS:
                    </td>
                    <?php foreach ($ciPeriodRows as $row): ?>
                    <td class="<?= $row['totals']['net_profit_loss'] >= 0 ? 'text-emerald-600' : 'text-red-600' ?>">
                        <?= format_money($row['totals']['net_profit_loss']) ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('glance-ci-chart');
    const downloadBtn = document.getElementById('glance-ci-chart-download');
    const typeButtons = document.querySelectorAll('#glance-ci-panel .chart-type-btn');
    if (!canvas || typeof Chart === 'undefined') return;

    const labels = <?= json_encode($ciChartLabels) ?>;
    const series = <?= json_encode($ciSeriesForChart) ?>;

    let chartInstance = null;
    let activeType = 'line';

    function isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }

    function chartColors() {
        const dark = isDarkMode();
        return {
            gridColor: dark ? 'rgba(148, 163, 184, 0.2)' : 'rgba(148, 163, 184, 0.35)',
            textColor: dark ? '#cbd5e1' : '#475569',
        };
    }

    function buildDatasets(type) {
        const isLineLike = type === 'line' || type === 'area';
        const isStacked = type === 'stacked';

        return series.map(function (item) {
            return {
                label: item.label,
                data: item.data,
                backgroundColor: isLineLike ? item.fill : item.bar,
                borderColor: item.border,
                borderWidth: isLineLike ? 2 : 1,
                borderRadius: type === 'bar' || isStacked ? 4 : 0,
                fill: type === 'area',
                tension: isLineLike ? 0.2 : 0,
                pointStyle: 'rectRot',
                pointRadius: isLineLike ? 4 : 0,
                pointHoverRadius: isLineLike ? 5 : 0,
                pointBackgroundColor: item.border,
                pointBorderColor: item.border,
            };
        });
    }

    function resolveChartType(type) {
        if (type === 'area') return 'line';
        if (type === 'stacked') return 'bar';
        return type;
    }

    function buildOptions(type) {
        const colors = chartColors();
        const isStacked = type === 'stacked';

        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const value = Number(ctx.raw || 0).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                            return ctx.dataset.label + ': ' + value;
                        },
                    },
                },
            },
            scales: {
                x: {
                    stacked: isStacked,
                    offset: true,
                    ticks: { color: colors.textColor, maxRotation: 0, minRotation: 0 },
                    grid: { color: colors.gridColor, drawBorder: true },
                    border: { color: colors.gridColor },
                },
                y: {
                    stacked: isStacked,
                    beginAtZero: true,
                    ticks: {
                        color: colors.textColor,
                        callback: function (value) {
                            return Number(value).toLocaleString();
                        },
                    },
                    grid: { color: colors.gridColor, drawBorder: true },
                    border: { color: colors.gridColor },
                },
            },
        };
    }

    function renderChart(type) {
        activeType = type;
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(canvas, {
            type: resolveChartType(type),
            data: { labels: labels, datasets: buildDatasets(type) },
            options: buildOptions(type),
        });
    }

    function setActiveButton(type) {
        typeButtons.forEach(function (btn) {
            const isActive = btn.dataset.chartType === type;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
    }

    typeButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const type = btn.dataset.chartType;
            if (!type || type === activeType) return;
            setActiveButton(type);
            renderChart(type);
        });
    });

    if (downloadBtn) {
        downloadBtn.addEventListener('click', function () {
            if (!chartInstance) return;

            const exportCanvas = document.createElement('canvas');
            exportCanvas.width = canvas.width;
            exportCanvas.height = canvas.height;
            const ctx = exportCanvas.getContext('2d');
            if (!ctx) return;

            ctx.fillStyle = isDarkMode() ? '#0f172a' : '#ffffff';
            ctx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
            ctx.drawImage(canvas, 0, 0);

            const link = document.createElement('a');
            const stamp = new Date().toISOString().slice(0, 10);
            link.download = 'linked-is-glance-' + activeType + '-' + stamp + '.png';
            link.href = exportCanvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }

    renderChart('line');
})();
</script>

<?php endif; ?>
