<?php

require_once __DIR__ . '/functions.php';

function ensure_linked_bs_tables(PDO $db): void
{
    static $done = false;
    if ($done) {
        return;
    }

    $check = $db->query("SHOW TABLES LIKE 'linked_bs_templates'");
    if (!$check || !$check->fetch()) {
        $sql = file_get_contents(__DIR__ . '/../database/migrations/add_linked_bs.sql');
        if ($sql) {
            $db->exec($sql);
        }
    }
    $done = true;
}

function get_linked_bs_template_id(PDO $db, int $companyId): ?int
{
    ensure_linked_bs_tables($db);
    $stmt = $db->prepare(
        'SELECT id FROM linked_bs_templates WHERE company_id = ? AND ' . not_deleted()
    );
    $stmt->execute([$companyId]);
    $row = $stmt->fetch();
    return $row ? (int) $row['id'] : null;
}

function get_or_create_linked_bs_template(PDO $db, int $companyId): int
{
    $templateId = get_linked_bs_template_id($db, $companyId);
    if ($templateId) {
        return $templateId;
    }

    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO linked_bs_templates (company_id, name) VALUES (?, ?)');
        $stmt->execute([$companyId, 'Linked BS']);
        $templateId = (int) $db->lastInsertId();
        seed_linked_bs_default_structure($db, $templateId);
        $db->commit();
        return $templateId;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

function seed_linked_bs_default_structure(PDO $db, int $templateId): void
{
    $defaultHeads = [
        [
            'label' => 'Assets',
            'number' => '1',
            'items' => [
                ['label' => 'Property and Equipment', 'number' => '1.1'],
                ['label' => 'Trade & Other Receivables', 'number' => '1.2'],
                ['label' => 'Inventory', 'number' => '1.3'],
                ['label' => 'Security Deposit', 'number' => '1.4'],
                ['label' => 'Employee Advances', 'number' => '1.5'],
                ['label' => 'Prepayments & Prepaid Expenses', 'number' => '1.6'],
                ['label' => 'Cash and Cash Equivalents', 'number' => '1.7'],
                ['label' => 'Bank', 'number' => '1.8'],
                ['label' => 'InPut VAT', 'number' => '1.9'],
            ],
            'formula' => 'add',
        ],
        [
            'label' => 'Equity & Liabilities',
            'number' => '2',
            'items' => [
                ['label' => 'Net Income', 'number' => '2.1'],
                ['label' => 'Drawings', 'number' => '2.2'],
                ['label' => 'Opening Balance Equity', 'number' => '2.3'],
                ['label' => 'Retained Earnings', 'number' => '2.4'],
                ['label' => 'Shareholder Current Account', 'number' => '2.5'],
                ['label' => 'Investment from Associates', 'number' => '2.6'],
                ['label' => 'Accounts Payable (A/P)', 'number' => '2.7'],
                ['label' => 'Machine Rent Payable', 'number' => '2.8'],
                ['label' => 'Loan from Infusion', 'number' => '2.9'],
                ['label' => 'Loan From Mr Saeed', 'number' => '2.10'],
                ['label' => 'Salaries & Wages Payable', 'number' => '2.11'],
                ['label' => 'Transguard Payable', 'number' => '2.12'],
                ['label' => 'Other Payables', 'number' => '2.13'],
            ],
            'formula' => 'add',
        ],
    ];

    $headStmt = $db->prepare(
        'INSERT INTO linked_bs_heads (template_id, label, head_number, sort_order) VALUES (?, ?, ?, ?)'
    );
    $itemStmt = $db->prepare(
        'INSERT INTO linked_bs_line_items (head_id, label, item_number, sort_order) VALUES (?, ?, ?, ?)'
    );
    $formulaStmt = $db->prepare(
        'INSERT INTO linked_bs_formula_terms (template_id, head_id, operation, sort_order) VALUES (?, ?, ?, ?)'
    );

    foreach ($defaultHeads as $headIndex => $head) {
        $headStmt->execute([$templateId, $head['label'], $head['number'], $headIndex + 1]);
        $headId = (int) $db->lastInsertId();

        foreach ($head['items'] as $itemIndex => $item) {
            $itemStmt->execute([$headId, $item['label'], $item['number'], $itemIndex + 1]);
        }

        $formulaStmt->execute([$templateId, $headId, $head['formula'], $headIndex + 1]);
    }
}

function get_linked_bs_structure(PDO $db, int $companyId): array
{
    $templateId = get_or_create_linked_bs_template($db, $companyId);

    $stmt = $db->prepare(
        'SELECT * FROM linked_bs_heads WHERE template_id = ? AND ' . not_deleted() . ' ORDER BY sort_order ASC, id ASC'
    );
    $stmt->execute([$templateId]);
    $heads = $stmt->fetchAll();

    $itemStmt = $db->prepare(
        'SELECT * FROM linked_bs_line_items WHERE head_id = ? AND ' . not_deleted() . ' ORDER BY sort_order ASC, id ASC'
    );

    $formulaStmt = $db->prepare(
        'SELECT head_id, operation, sort_order FROM linked_bs_formula_terms
         WHERE template_id = ? AND ' . not_deleted() . ' ORDER BY sort_order ASC, id ASC'
    );
    $formulaStmt->execute([$templateId]);
    $formulaByHead = [];
    foreach ($formulaStmt->fetchAll() as $row) {
        $formulaByHead[(int) $row['head_id']] = $row['operation'];
    }

    $structure = [];
    foreach ($heads as $head) {
        $headId = (int) $head['id'];
        $itemStmt->execute([$headId]);
        $structure[] = [
            'id' => $headId,
            'label' => $head['label'],
            'head_number' => $head['head_number'],
            'sort_order' => (int) $head['sort_order'],
            'formula_operation' => $formulaByHead[$headId] ?? null,
            'items' => array_map(static function ($item) {
                return [
                    'id' => (int) $item['id'],
                    'label' => $item['label'],
                    'item_number' => $item['item_number'],
                    'sort_order' => (int) $item['sort_order'],
                ];
            }, $itemStmt->fetchAll()),
        ];
    }

    return [
        'template_id' => $templateId,
        'heads' => $structure,
    ];
}

function get_linked_bs_entry_values(PDO $db, int $branchId, int $year, int $month): array
{
    ensure_linked_bs_tables($db);
    $stmt = $db->prepare(
        'SELECT line_item_id, amount, entry_date FROM linked_bs_entries
         WHERE branch_id = ? AND period_year = ? AND period_month = ? AND ' . not_deleted()
    );
    $stmt->execute([$branchId, $year, $month]);
    $values = [];
    $entryDate = null;
    foreach ($stmt->fetchAll() as $row) {
        $values[(int) $row['line_item_id']] = (float) $row['amount'];
        if ($row['entry_date']) {
            $entryDate = $row['entry_date'];
        }
    }
    return ['values' => $values, 'entry_date' => $entryDate];
}

function calculate_linked_bs_totals(array $structure, array $values): array
{
    $headTotals = [];
    $headDetails = [];

    foreach ($structure['heads'] as $head) {
        $headSum = 0.0;
        $itemValues = [];
        foreach ($head['items'] as $item) {
            $amount = (float) ($values[$item['id']] ?? 0);
            $itemValues[$item['id']] = $amount;
            $headSum += $amount;
        }
        $headTotals[$head['id']] = $headSum;
        $headDetails[$head['id']] = $itemValues;
    }

    $calculatedTotal = 0.0;
    foreach ($structure['heads'] as $head) {
        $operation = $head['formula_operation'] ?? null;
        if (!$operation) {
            continue;
        }
        $headTotal = $headTotals[$head['id']] ?? 0;
        if ($operation === 'add') {
            $calculatedTotal += $headTotal;
        } elseif ($operation === 'subtract') {
            $calculatedTotal -= $headTotal;
        }
    }

    return [
        'head_totals' => $headTotals,
        'item_values' => $headDetails,
        'calculated_total' => $calculatedTotal,
    ];
}

function save_linked_bs_entries(
    PDO $db,
    int $branchId,
    int $year,
    int $month,
    ?string $entryDate,
    array $items,
    array $structure
): void {
    ensure_linked_bs_tables($db);

    $validItemIds = [];
    foreach ($structure['heads'] as $head) {
        foreach ($head['items'] as $item) {
            $validItemIds[] = (int) $item['id'];
        }
    }

    $stmt = $db->prepare(
        'INSERT INTO linked_bs_entries (branch_id, period_year, period_month, entry_date, line_item_id, amount)
         VALUES (?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE amount = VALUES(amount), entry_date = VALUES(entry_date),
         deleted_at = NULL, updated_at = CURRENT_TIMESTAMP'
    );

    foreach ($validItemIds as $itemId) {
        $raw = $items[$itemId] ?? $items[(string) $itemId] ?? '0';
        $amount = (float) str_replace(',', '', (string) $raw);
        $stmt->execute([$branchId, $year, $month, $entryDate ?: null, $itemId, $amount]);
    }
}

function save_linked_bs_formula(PDO $db, int $templateId, array $formula): void
{
    ensure_linked_bs_tables($db);

    $stmt = $db->prepare('DELETE FROM linked_bs_formula_terms WHERE template_id = ?');
    $stmt->execute([$templateId]);

    $insert = $db->prepare(
        'INSERT INTO linked_bs_formula_terms (template_id, head_id, operation, sort_order) VALUES (?, ?, ?, ?)'
    );

    $order = 1;
    $seenHeads = [];
    foreach ($formula as $headId => $operation) {
        $headId = (int) $headId;
        if (!$headId || isset($seenHeads[$headId])) {
            continue;
        }
        if (!in_array($operation, ['add', 'subtract'], true)) {
            continue;
        }
        $seenHeads[$headId] = true;
        $insert->execute([$templateId, $headId, $operation, $order++]);
    }
}

function build_linked_bs_formula_preview(array $heads): string
{
    $parts = [];
    foreach ($heads as $head) {
        $op = $head['formula_operation'] ?? null;
        if ($op === 'add') {
            $parts[] = ['op' => '+', 'label' => $head['label']];
        } elseif ($op === 'subtract') {
            $parts[] = ['op' => '−', 'label' => $head['label']];
        }
    }

    if (empty($parts)) {
        return 'Calculated Total = (no heads selected)';
    }

    $expr = 'Calculated Total = ';
    foreach ($parts as $i => $part) {
        if ($i === 0) {
            $expr .= $part['label'];
        } else {
            $expr .= ' ' . $part['op'] . ' ' . $part['label'];
        }
    }
    return $expr;
}

function linked_bs_next_sort_order(PDO $db, string $table, string $column, int $parentId): int
{
    $allowed = [
        'linked_bs_heads' => 'template_id',
        'linked_bs_line_items' => 'head_id',
        'linked_bs_formula_terms' => 'template_id',
    ];
    if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
        return 1;
    }

    $stmt = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM {$table} WHERE {$column} = ? AND deleted_at IS NULL");
    $stmt->execute([$parentId]);
    return (int) $stmt->fetchColumn();
}
