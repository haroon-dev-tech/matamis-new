<?php
/**
 * Renders SOMFP financial position table
 * @var array $somfpConfig
 * @var array $values keyed by line_item_key
 * @var bool $editable
 * @var string $prefix form field prefix e.g. "items" or "branches[1][items]"
 */
$somfpConfig = $somfpConfig ?? require __DIR__ . '/../../config/somfp.php';
$values = $values ?? [];
$editable = $editable ?? false;
$prefix = $prefix ?? 'items';
$totals = calculate_somfp_totals($values);
?>

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                <th class="px-4 py-3 text-left font-semibold">Description</th>
                <th class="px-4 py-3 text-right font-semibold w-48">Amount (AED)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        <?php foreach ($somfpConfig['sections'] as $sectionKey => $section): ?>
            <tr class="somfp-row-section">
                <td class="px-4 py-3"><?= e($section['number']) ?></td>
                <td class="px-4 py-3" colspan="2"><?= e($section['label']) ?></td>
            </tr>
            <?php
            $sectionItemNum = 0;
            foreach ($section['groups'] as $groupKey => $group):
                $sectionItemNum++;
            ?>
            <tr class="somfp-row-group">
                <td class="px-4 py-2 pl-6"><?= e($group['number']) ?></td>
                <td class="px-4 py-2"><?= e($group['label']) ?></td>
                <td class="px-4 py-2 text-right font-mono" id="group-<?= e($groupKey) ?>"><?= format_money($totals['groups'][$groupKey] ?? 0) ?></td>
            </tr>
            <?php
            $subNum = 0;
            foreach ($group['items'] as $itemKey => $label):
                $subNum++;
                $itemNumber = $group['number'] . '.' . $subNum;
                $amount = (float) ($values[$itemKey] ?? 0);
            ?>
            <tr>
                <td class="px-4 py-2 pl-10 text-slate-500"><?= e($itemNumber) ?></td>
                <td class="px-4 py-2 text-slate-700 dark:text-slate-300"><?= e($label) ?></td>
                <td class="px-4 py-2">
                    <?php if ($editable): ?>
                    <input type="text"
                           name="<?= e($prefix) ?>[<?= e($itemKey) ?>]"
                           data-line-item="<?= e($itemKey) ?>"
                           class="money-input input-field"
                           value="<?= $amount != 0 ? format_money($amount) : '' ?>"
                           placeholder="0.00">
                    <?php else: ?>
                    <span class="block text-right font-mono tabular-nums"><?= format_money($amount) ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <tr class="somfp-row-total">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">
                    <?php if ($sectionKey === 'assets'): ?>
                    Total Assets (X) = Fixed Assets + Current Assets
                    <?php else: ?>
                    Total Equity &amp; Liabilities (Y) = Equity + Non-current Liabilities + Current Liabilities
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-right font-mono" id="<?= $sectionKey === 'assets' ? 'total-assets' : 'total-el' ?>">
                    <?= format_money($sectionKey === 'assets' ? $totals['total_assets'] : $totals['total_equity_liabilities']) ?>
                </td>
            </tr>
        <?php endforeach; ?>

            <tr class="somfp-row-error <?= abs($totals['error']) < 0.01 ? 'balanced' : 'unbalanced' ?>" id="error-row">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Error (X − Y)</td>
                <td class="px-4 py-3 text-right font-mono" id="error-xy"><?= format_money($totals['error']) ?></td>
            </tr>
        </tbody>
    </table>
</div>
