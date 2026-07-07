-- Linked BS — dynamic balance sheet structure & entries
USE matamis;

CREATE TABLE IF NOT EXISTS linked_bs_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL DEFAULT 'Linked BS',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_linked_bs_company (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS linked_bs_heads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_id INT UNSIGNED NOT NULL,
    label VARCHAR(255) NOT NULL,
    head_number VARCHAR(20) DEFAULT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (template_id) REFERENCES linked_bs_templates(id) ON DELETE CASCADE,
    INDEX idx_linked_bs_heads_template (template_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS linked_bs_line_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    head_id INT UNSIGNED NOT NULL,
    label VARCHAR(255) NOT NULL,
    item_number VARCHAR(20) DEFAULT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (head_id) REFERENCES linked_bs_heads(id) ON DELETE CASCADE,
    INDEX idx_linked_bs_items_head (head_id, sort_order)
) ENGINE=InnoDB;

-- Calculated total: which head totals are added or subtracted
CREATE TABLE IF NOT EXISTS linked_bs_formula_terms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_id INT UNSIGNED NOT NULL,
    head_id INT UNSIGNED NOT NULL,
    operation ENUM('add', 'subtract') NOT NULL DEFAULT 'add',
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_linked_bs_formula_head (template_id, head_id),
    FOREIGN KEY (template_id) REFERENCES linked_bs_templates(id) ON DELETE CASCADE,
    FOREIGN KEY (head_id) REFERENCES linked_bs_heads(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS linked_bs_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id INT UNSIGNED NOT NULL,
    period_year SMALLINT UNSIGNED NOT NULL,
    period_month TINYINT UNSIGNED NOT NULL,
    entry_date DATE DEFAULT NULL,
    line_item_id INT UNSIGNED NOT NULL,
    amount DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_linked_bs_entry (branch_id, period_year, period_month, line_item_id),
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (line_item_id) REFERENCES linked_bs_line_items(id) ON DELETE CASCADE,
    INDEX idx_linked_bs_period (period_year, period_month)
) ENGINE=InnoDB;
