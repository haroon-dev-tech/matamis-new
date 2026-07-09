<?php
/**
 * @var array $structure
 * @var array $previousValues keyed by line_item_id
 * @var array $currentValues keyed by line_item_id
 * @var array $previousTotals from calculate_linked_is_totals
 * @var array $currentTotals from calculate_linked_is_totals
 * @var int $previousYear
 * @var int $previousMonth
 * @var int $currentYear
 * @var int $currentMonth
 */
$previousTotals = $previousTotals ?? calculate_linked_is_totals($structure, $previousValues);
$currentTotals = $currentTotals ?? calculate_linked_is_totals($structure, $currentValues);
$previousLabel = MONTHS[$previousMonth] . ' ' . $previousYear;
$currentLabel = MONTHS[$currentMonth] . ' ' . $currentYear;
?>

<div class="overflow-x-auto">
    <table class="w-full text-sm" id="linked-is-compare-table">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                <th class="px-4 py-3 text-left font-semibold">Description</th>
                <th class="px-4 py-3 text-right font-semibold w-40">Previous Month<br><span class="text-xs font-normal text-slate-500"><?= e($previousLabel) ?></span></th>
                <th class="px-4 py-3 text-right font-semibold w-40">Last Month<br><span class="text-xs font-normal text-slate-500"><?= e($currentLabel) ?></span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        <?php foreach ($structure['heads'] as $head): ?>
            <tr class="somfp-row-section">
                <td class="px-4 py-3"><?= e($head['head_number'] ?: '') ?></td>
                <td class="px-4 py-3 font-semibold"><?= e($head['label']) ?></td>
                <td class="px-4 py-3 text-right font-mono font-semibold tabular-nums">
                    <?= format_money($previousTotals['head_totals'][$head['id']] ?? 0) ?>
                </td>
                <td class="px-4 py-3 text-right font-mono font-semibold tabular-nums">
                    <?= format_money($currentTotals['head_totals'][$head['id']] ?? 0) ?>
                </td>
            </tr>
            <?php foreach ($head['items'] as $item): ?>
            <?php
            $previousAmount = (float) ($previousValues[$item['id']] ?? 0);
            $currentAmount = (float) ($currentValues[$item['id']] ?? 0);
            ?>
            <tr>
                <td class="px-4 py-2 pl-6 text-slate-500"><?= e($item['item_number'] ?: '') ?></td>
                <td class="px-4 py-2 text-slate-700 dark:text-slate-300"><?= e($item['label']) ?></td>
                <td class="px-4 py-2 text-right font-mono tabular-nums"><?= format_money($previousAmount) ?></td>
                <td class="px-4 py-2 text-right font-mono tabular-nums"><?= format_money($currentAmount) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>

            <tr class="somci-row-final">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3 font-bold">Net Profit / Loss</td>
                <td class="px-4 py-3 text-right font-mono font-bold tabular-nums">
                    <?= format_money($previousTotals['net_profit_loss']) ?>
                </td>
                <td class="px-4 py-3 text-right font-mono font-bold tabular-nums">
                    <?= format_money($currentTotals['net_profit_loss']) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
