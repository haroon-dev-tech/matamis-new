<?php

require_once __DIR__ . '/functions.php';

function ensure_linked_is_tables(PDO $db): void
{
    static $done = false;
    if ($done) {
        return;
    }

    $check = $db->query("SHOW TABLES LIKE 'linked_is_templates'");
    if (!$check || !$check->fetch()) {
        $sql = file_get_contents(__DIR__ . '/../database/migrations/add_linked_is.sql');
        if ($sql) {
            $db->exec($sql);
        }
    }
    $done = true;
}

function get_linked_is_template_id(PDO $db, int $companyId): ?int
{
    ensure_linked_is_tables($db);
    $stmt = $db->prepare(
        'SELECT id FROM linked_is_templates WHERE company_id = ? AND ' . not_deleted()
    );
    $stmt->execute([$companyId]);
    $row = $stmt->fetch();
    return $row ? (int) $row['id'] : null;
}

function get_or_create_linked_is_template(PDO $db, int $companyId): int
{
    $templateId = get_linked_is_template_id($db, $companyId);
    if ($templateId) {
        return $templateId;
    }

    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO linked_is_templates (company_id, name) VALUES (?, ?)');
        $stmt->execute([$companyId, 'Linked IS']);
        $templateId = (int) $db->lastInsertId();
        seed_linked_is_default_structure($db, $templateId);
        $db->commit();
        return $templateId;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

function seed_linked_is_default_structure(PDO $db, int $templateId): void
{
    $defaultHeads = [
        [
            'label' => 'Revenue',
            'number' => '1',
            'items' => [
                ['label' => 'Sales', 'number' => '1.1'],
                ['label' => "E-Commerce's/Online Sale", 'number' => '1.2'],
                ['label' => 'Sales Discounts', 'number' => '1.3'],
            ],
            'formula' => 'add',
        ],
        [
            'label' => 'Direct Expenses',
            'number' => '2',
            'items' => [
                ['label' => 'Cost of Sales', 'number' => '2.1'],
            ],
            'formula' => 'subtract',
        ],
        [
            'label' => 'Operating & Administrative Expenses',
            'number' => '3',
            'items' => [
                ['label' => 'Salary & wages', 'number' => '3.1'],
                ['label' => 'Admin. Expenses', 'number' => '3.2'],
                ['label' => 'Office Rent Expenses', 'number' => '3.3'],
                ['label' => 'Utility Expenses', 'number' => '3.4'],
            ],
            'formula' => 'subtract',
        ],
        [
            'label' => 'Other Expenses',
            'number' => '4',
            'items' => [
                ['label' => 'Bank Charges', 'number' => '4.1'],
                ['label' => 'Depreciation', 'number' => '4.2'],
            ],
            'formula' => 'subtract',
        ],
        [
            'label' => 'Other Income',
            'number' => '5',
            'items' => [
                ['label' => 'Other Income', 'number' => '5.1'],
            ],
            'formula' => 'add',
        ],
    ];

    $headStmt = $db->prepare(
        'INSERT INTO linked_is_heads (template_id, label, head_number, sort_order) VALUES (?, ?, ?, ?)'
    );
    $itemStmt = $db->prepare(
        'INSERT INTO linked_is_line_items (head_id, label, item_number, sort_order) VALUES (?, ?, ?, ?)'
    );
    $formulaStmt = $db->prepare(
        'INSERT INTO linked_is_formula_terms (template_id, head_id, operation, sort_order) VALUES (?, ?, ?, ?)'
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

function get_linked_is_structure(PDO $db, int $companyId): array
{
    $templateId = get_or_create_linked_is_template($db, $companyId);

    $stmt = $db->prepare(
        'SELECT * FROM linked_is_heads WHERE template_id = ? AND ' . not_deleted() . ' ORDER BY sort_order ASC, id ASC'
    );
    $stmt->execute([$templateId]);
    $heads = $stmt->fetchAll();

    $itemStmt = $db->prepare(
        'SELECT * FROM linked_is_line_items WHERE head_id = ? AND ' . not_deleted() . ' ORDER BY sort_order ASC, id ASC'
    );

    $formulaStmt = $db->prepare(
        'SELECT head_id, operation, sort_order FROM linked_is_formula_terms
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

function get_linked_is_entry_values(PDO $db, int $branchId, int $year, int $month): array
{
    ensure_linked_is_tables($db);
    $stmt = $db->prepare(
        'SELECT line_item_id, amount, entry_date FROM linked_is_entries
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

function get_previous_period(int $year, int $month): array
{
    if ($month <= 1) {
        return ['year' => $year - 1, 'month' => 12];
    }

    return ['year' => $year, 'month' => $month - 1];
}

function get_consolidated_linked_is_entry_values(
    PDO $db,
    int $companyId,
    int $year,
    int $month,
    ?int $branchId = null
): array {
    if ($branchId) {
        return get_linked_is_entry_values($db, $branchId, $year, $month);
    }

    ensure_linked_is_tables($db);
    $branches = get_company_branches($db, $companyId);
    $values = [];
    $entryDate = null;

    foreach ($branches as $branch) {
        $entryData = get_linked_is_entry_values($db, (int) $branch['id'], $year, $month);
        foreach ($entryData['values'] as $itemId => $amount) {
            $values[$itemId] = ($values[$itemId] ?? 0) + $amount;
        }
        if ($entryData['entry_date'] && (!$entryDate || $entryData['entry_date'] > $entryDate)) {
            $entryDate = $entryData['entry_date'];
        }
    }

    return ['values' => $values, 'entry_date' => $entryDate];
}

function calculate_linked_is_totals(array $structure, array $values): array
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

    $netProfitLoss = 0.0;
    foreach ($structure['heads'] as $head) {
        $operation = $head['formula_operation'] ?? null;
        if (!$operation) {
            continue;
        }
        $headTotal = $headTotals[$head['id']] ?? 0;
        if ($operation === 'add') {
            $netProfitLoss += $headTotal;
        } elseif ($operation === 'subtract') {
            $netProfitLoss -= $headTotal;
        }
    }

    return [
        'head_totals' => $headTotals,
        'item_values' => $headDetails,
        'net_profit_loss' => $netProfitLoss,
    ];
}

function save_linked_is_entries(
    PDO $db,
    int $branchId,
    int $year,
    int $month,
    ?string $entryDate,
    array $items,
    array $structure
): void {
    ensure_linked_is_tables($db);

    $validItemIds = [];
    foreach ($structure['heads'] as $head) {
        foreach ($head['items'] as $item) {
            $validItemIds[] = (int) $item['id'];
        }
    }

    $stmt = $db->prepare(
        'INSERT INTO linked_is_entries (branch_id, period_year, period_month, entry_date, line_item_id, amount)
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

function save_linked_is_formula(PDO $db, int $templateId, array $formula): void
{
    ensure_linked_is_tables($db);

    // Hard delete — soft delete leaves rows that violate uq_formula_head (template_id, head_id)
    $stmt = $db->prepare('DELETE FROM linked_is_formula_terms WHERE template_id = ?');
    $stmt->execute([$templateId]);

    $insert = $db->prepare(
        'INSERT INTO linked_is_formula_terms (template_id, head_id, operation, sort_order) VALUES (?, ?, ?, ?)'
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

function build_linked_is_formula_preview(array $heads): string
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
        return 'Net Profit / Loss = (no heads selected)';
    }

    $expr = 'Net Profit / Loss = ';
    foreach ($parts as $i => $part) {
        if ($i === 0) {
            $expr .= $part['label'];
        } else {
            $expr .= ' ' . $part['op'] . ' ' . $part['label'];
        }
    }
    return $expr;
}

function linked_is_next_sort_order(PDO $db, string $table, string $column, int $parentId): int
{
    $allowed = [
        'linked_is_heads' => 'template_id',
        'linked_is_line_items' => 'head_id',
        'linked_is_formula_terms' => 'template_id',
    ];
    if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
        return 1;
    }

    $stmt = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM {$table} WHERE {$column} = ? AND deleted_at IS NULL");
    $stmt->execute([$parentId]);
    return (int) $stmt->fetchColumn();
}
