<?php
/**
 * @var array $structure
 * @var array $values keyed by line_item_id
 * @var array $totals from calculate_linked_bs_totals
 * @var bool $editable
 */
$editable = $editable ?? true;
$totals = $totals ?? calculate_linked_bs_totals($structure, $values);
?>

<div class="overflow-x-auto">
    <table class="w-full text-sm" id="linked-bs-table">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                <th class="px-4 py-3 text-left font-semibold">Description</th>
                <th class="px-4 py-3 text-right font-semibold w-48">Amount (AED)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        <?php foreach ($structure['heads'] as $head): ?>
            <tr class="somfp-row-section">
                <td class="px-4 py-3"><?= e($head['head_number'] ?: '') ?></td>
                <td class="px-4 py-3 font-semibold"><?= e($head['label']) ?></td>
                <td class="px-4 py-3 text-right font-mono font-semibold linked-bs-head-total" data-head-id="<?= $head['id'] ?>">
                    <?= format_money($totals['head_totals'][$head['id']] ?? 0) ?>
                </td>
            </tr>
            <?php foreach ($head['items'] as $item): ?>
            <?php $amount = (float) ($values[$item['id']] ?? 0); ?>
            <tr>
                <td class="px-4 py-2 pl-6 text-slate-500"><?= e($item['item_number'] ?: '') ?></td>
                <td class="px-4 py-2 text-slate-700 dark:text-slate-300"><?= e($item['label']) ?></td>
                <td class="px-4 py-2">
                    <?php if ($editable): ?>
                    <input type="text"
                           name="items[<?= $item['id'] ?>]"
                           class="money-input input-field linked-bs-item-input"
                           data-head-id="<?= $head['id'] ?>"
                           data-item-id="<?= $item['id'] ?>"
                           value="<?= $amount != 0 ? format_money($amount) : '' ?>"
                           placeholder="0.00">
                    <?php else: ?>
                    <span class="block text-right font-mono tabular-nums"><?= format_money($amount) ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>

            <tr class="somci-row-final">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3 font-bold">Calculated Total</td>
                <td class="px-4 py-3 text-right font-mono font-bold" id="linked-bs-calculated-total">
                    <?= format_money($totals['calculated_total']) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
