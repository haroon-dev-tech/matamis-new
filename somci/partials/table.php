<?php
/**
 * Renders SOMCI income statement table
 * @var array $somciConfig
 * @var array $values keyed by line_item_key
 * @var bool $editable
 * @var string $prefix form field prefix
 */
$somciConfig = $somciConfig ?? require __DIR__ . '/../../config/somci.php';
$values = $values ?? [];
$editable = $editable ?? false;
$prefix = $prefix ?? 'items';
$totals = calculate_somci_totals($values);
?>

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <th class="w-16 px-4 py-3 text-left font-semibold">#</th>
                <th class="px-4 py-3 text-left font-semibold">Description</th>
                <th class="w-48 px-4 py-3 text-right font-semibold">Amount (AED)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        <?php foreach ($somciConfig['rows'] as $row): ?>
            <?php if ($row['type'] === 'section'): ?>
            <tr class="somfp-row-section">
                <td class="px-4 py-3"><?= e($row['number']) ?></td>
                <td class="px-4 py-3" colspan="2"><?= e($row['label']) ?></td>
            </tr>
            <?php elseif ($row['type'] === 'item'): ?>
            <?php $amount = (float) ($values[$row['key']] ?? 0); ?>
            <tr>
                <td class="px-4 py-2 pl-10 text-slate-500"><?= e($row['number']) ?></td>
                <td class="px-4 py-2 text-slate-700 dark:text-slate-300"><?= e($row['label']) ?></td>
                <td class="px-4 py-2">
                    <?php if ($editable): ?>
                    <input type="text"
                           name="<?= e($prefix) ?>[<?= e($row['key']) ?>]"
                           data-line-item="<?= e($row['key']) ?>"
                           class="money-input input-field"
                           value="<?= $amount != 0 ? format_money($amount) : '' ?>"
                           placeholder="0.00">
                    <?php else: ?>
                    <span class="block text-right font-mono tabular-nums"><?= format_money($amount) ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php elseif ($row['type'] === 'calculated'): ?>
            <?php
            $calcKey = $row['key'];
            $calcAmount = (float) ($totals[$calcKey] ?? 0);
            $isHighlight = !empty($row['highlight']);
            $rowClass = $isHighlight ? 'somfp-row-total somci-row-final' : 'somfp-row-total';
            ?>
            <tr class="<?= $rowClass ?>">
                <td class="px-4 py-3"><?= e($row['number'] ?? '') ?></td>
                <td class="px-4 py-3"><?= e($row['label']) ?></td>
                <td class="px-4 py-3 text-right font-mono" id="calc-<?= e($calcKey) ?>"><?= format_money($calcAmount) ?></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
