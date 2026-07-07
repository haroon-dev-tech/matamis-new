<?php
$pageTitle = 'Linked IS — Structure';
$activeNav = 'linked-is';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_is.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? $_POST['company_id'] ?? 0);

$companies = get_accessible_companies($db, $userId, 'linked_is');

if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $companyId && can_access_company($db, $companyId, $userId, 'linked_is')) {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } else {
        $action = $_POST['action'] ?? '';
        $templateId = get_or_create_linked_is_template($db, $companyId);

        try {
            if ($action === 'add_head') {
                $label = trim($_POST['head_label'] ?? '');
                $number = trim($_POST['head_number'] ?? '');
                if ($label === '') {
                    throw new RuntimeException('Head label is required.');
                }
                $sort = linked_is_next_sort_order($db, 'linked_is_heads', 'template_id', $templateId);
                $stmt = $db->prepare(
                    'INSERT INTO linked_is_heads (template_id, label, head_number, sort_order) VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$templateId, $label, $number ?: null, $sort]);
                flash('success', 'Head added.');
            } elseif ($action === 'delete_head') {
                $headId = (int) ($_POST['head_id'] ?? 0);
                soft_delete_rows($db, 'linked_is_line_items', 'head_id = ?', [$headId]);
                soft_delete_rows($db, 'linked_is_formula_terms', 'head_id = ?', [$headId]);
                soft_delete_rows($db, 'linked_is_heads', 'id = ? AND template_id = ?', [$headId, $templateId]);
                flash('success', 'Head deleted.');
            } elseif ($action === 'add_item') {
                $headId = (int) ($_POST['head_id'] ?? 0);
                $label = trim($_POST['item_label'] ?? '');
                $number = trim($_POST['item_number'] ?? '');
                if ($label === '' || !$headId) {
                    throw new RuntimeException('Sub-heading label is required.');
                }
                $sort = linked_is_next_sort_order($db, 'linked_is_line_items', 'head_id', $headId);
                $stmt = $db->prepare(
                    'INSERT INTO linked_is_line_items (head_id, label, item_number, sort_order) VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$headId, $label, $number ?: null, $sort]);
                flash('success', 'Sub-heading added.');
            } elseif ($action === 'delete_item') {
                $itemId = (int) ($_POST['item_id'] ?? 0);
                soft_delete_rows($db, 'linked_is_line_items', 'id = ?', [$itemId]);
                flash('success', 'Sub-heading deleted.');
            } elseif ($action === 'save_formula') {
                save_linked_is_formula($db, $templateId, $_POST['formula'] ?? []);
                flash('success', 'Net profit/loss formula saved.');
            }

            redirect('/linked-is/structure.php?company_id=' . $companyId);
        } catch (Exception $e) {
            $error = $e->getMessage() ?: 'Action failed.';
        }
    }
}

$structure = ($companyId && can_access_company($db, $companyId, $userId, 'linked_is'))
    ? get_linked_is_structure($db, $companyId)
    : ['heads' => []];

$stmt = $db->prepare('SELECT name FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$companyName = $stmt->fetchColumn() ?: 'Company';

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/linked-is/index.php" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Linked IS
    </a>
    <?php if ($companyId): ?>
    <a href="<?= BASE_URL ?>/linked-is/entry.php?company_id=<?= $companyId ?>" class="btn-primary text-sm">Go to Data Entry</a>
    <?php endif; ?>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center text-slate-500">No companies registered.</div>
<?php else: ?>

<div class="card mb-6 p-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="min-w-[220px] flex-1">
            <label class="mb-1.5 block text-sm font-medium">Company</label>
            <select name="company_id" class="input-field" onchange="this.form.submit()">
                <?php foreach ($companies as $co): ?>
                <option value="<?= $co['id'] ?>" <?= $co['id'] == $companyId ? 'selected' : '' ?>><?= e($co['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if ($error): ?>
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
<?php endif; ?>

<div class="card mb-6 p-6">
    <h2 class="mb-4 text-lg font-semibold">Add Head</h2>
    <form method="POST" class="flex flex-wrap items-end gap-3">
        <?= csrf_field() ?>
        <input type="hidden" name="company_id" value="<?= $companyId ?>">
        <input type="hidden" name="action" value="add_head">
        <div class="min-w-[120px]">
            <label class="mb-1 block text-xs font-medium text-slate-500">Head #</label>
            <input type="text" name="head_number" class="input-field" placeholder="e.g. 1">
        </div>
        <div class="min-w-[240px] flex-1">
            <label class="mb-1 block text-xs font-medium text-slate-500">Head Label</label>
            <input type="text" name="head_label" class="input-field" placeholder="e.g. Revenue" required>
        </div>
        <button type="submit" class="btn-primary">Add Head</button>
    </form>
</div>

<?php foreach ($structure['heads'] as $head): ?>
<div class="card mb-6 overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-800/50">
        <div>
            <h3 class="font-semibold">
                <?php if ($head['head_number']): ?><span class="text-slate-500"><?= e($head['head_number']) ?>.</span> <?php endif; ?>
                <?= e($head['label']) ?>
            </h3>
            <p class="text-xs text-slate-500"><?= count($head['items']) ?> sub-heading(s) — head total = sum of sub-headings</p>
        </div>
        <form method="POST" onsubmit="return confirm('Delete this head and all its sub-headings?');">
            <?= csrf_field() ?>
            <input type="hidden" name="company_id" value="<?= $companyId ?>">
            <input type="hidden" name="action" value="delete_head">
            <input type="hidden" name="head_id" value="<?= $head['id'] ?>">
            <button type="submit" class="btn-action btn-action-delete">Delete Head</button>
        </form>
    </div>

    <?php if (empty($head['items'])): ?>
    <div class="px-6 py-4 text-sm text-slate-500">No sub-headings yet.</div>
    <?php else: ?>
    <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <?php foreach ($head['items'] as $item): ?>
        <div class="flex items-center justify-between px-6 py-3">
            <span class="text-sm">
                <?php if ($item['item_number']): ?><span class="text-slate-500"><?= e($item['item_number']) ?></span> <?php endif; ?>
                <?= e($item['label']) ?>
            </span>
            <form method="POST" class="inline" onsubmit="return confirm('Delete this sub-heading?');">
                <?= csrf_field() ?>
                <input type="hidden" name="company_id" value="<?= $companyId ?>">
                <input type="hidden" name="action" value="delete_item">
                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                <button type="submit" class="text-xs text-red-600 hover:text-red-700">Remove</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
        <form method="POST" class="flex flex-wrap items-end gap-3">
            <?= csrf_field() ?>
            <input type="hidden" name="company_id" value="<?= $companyId ?>">
            <input type="hidden" name="action" value="add_item">
            <input type="hidden" name="head_id" value="<?= $head['id'] ?>">
            <div class="w-24">
                <label class="mb-1 block text-xs text-slate-500">#</label>
                <input type="text" name="item_number" class="input-field text-sm" placeholder="1.1">
            </div>
            <div class="min-w-[200px] flex-1">
                <label class="mb-1 block text-xs text-slate-500">Sub-heading Label</label>
                <input type="text" name="item_label" class="input-field text-sm" placeholder="e.g. Sales" required>
            </div>
            <button type="submit" class="btn-secondary text-sm">Add Sub-heading</button>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php if (!empty($structure['heads'])): ?>
<div class="card overflow-hidden">
    <div class="border-b border-slate-200 bg-gradient-to-r from-brand-50 to-slate-50 px-6 py-5 dark:border-slate-800 dark:from-brand-950 dark:to-slate-900">
        <h2 class="text-lg font-semibold">Net Profit / Loss Formula</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Set how each head total contributes to the final net profit or loss.
        </p>
        <div id="formula-preview" class="formula-preview mt-4 rounded-xl border border-brand-200 bg-white px-4 py-3 text-sm font-medium text-brand-900 dark:border-brand-800 dark:bg-slate-900 dark:text-brand-200">
            <?= e(build_linked_is_formula_preview($structure['heads'])) ?>
        </div>
    </div>

    <form method="POST" id="formula-form">
        <?= csrf_field() ?>
        <input type="hidden" name="company_id" value="<?= $companyId ?>">
        <input type="hidden" name="action" value="save_formula">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                        <th class="px-6 py-3 text-left font-semibold w-16">#</th>
                        <th class="px-6 py-3 text-left font-semibold">Head</th>
                        <th class="px-6 py-3 text-left font-semibold w-56">Contribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php foreach ($structure['heads'] as $head): ?>
                    <?php $op = $head['formula_operation'] ?? ''; ?>
                    <tr class="formula-row hover:bg-slate-50 dark:hover:bg-slate-800/30">
                        <td class="px-6 py-4 text-slate-500"><?= e($head['head_number'] ?: '—') ?></td>
                        <td class="px-6 py-4">
                            <p class="font-medium"><?= e($head['label']) ?></p>
                            <p class="text-xs text-slate-500"><?= count($head['items']) ?> sub-heading(s) · auto-summed</p>
                        </td>
                        <td class="px-6 py-4">
                            <select name="formula[<?= $head['id'] ?>]"
                                    class="input-field formula-select text-sm"
                                    data-head-label="<?= e($head['label']) ?>">
                                <option value="" <?= $op === '' ? 'selected' : '' ?>>— Not included —</option>
                                <option value="add" <?= $op === 'add' ? 'selected' : '' ?>>➕ Add to net profit</option>
                                <option value="subtract" <?= $op === 'subtract' ? 'selected' : '' ?>>➖ Subtract from net profit</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-6 py-4 dark:border-slate-800">
            <p class="text-xs text-slate-500">
                Example: Revenue <strong>Add</strong> + Direct Expenses <strong>Subtract</strong> → Net = Revenue − Direct Expenses
            </p>
            <button type="submit" class="btn-primary">Save Formula</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const preview = document.getElementById('formula-preview');
    const selects = document.querySelectorAll('.formula-select');
    if (!preview || !selects.length) return;

    function updatePreview() {
        const parts = [];
        selects.forEach(function (sel) {
            const label = sel.dataset.headLabel || 'Head';
            if (sel.value === 'add') parts.push({ op: '+', label: label });
            else if (sel.value === 'subtract') parts.push({ op: '−', label: label });
        });

        if (!parts.length) {
            preview.textContent = 'Net Profit / Loss = (no heads selected)';
            return;
        }

        let expr = 'Net Profit / Loss = ';
        parts.forEach(function (p, i) {
            expr += (i === 0 ? '' : ' ' + p.op + ' ') + p.label;
        });
        preview.textContent = expr;
    }

    selects.forEach(function (sel) {
        sel.addEventListener('change', updatePreview);
    });
});
</script>
<?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
