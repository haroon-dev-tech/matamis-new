-- Mata Consultancy MIS Database Schema
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS matamis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE matamis;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB;

-- Companies
CREATE TABLE IF NOT EXISTS companies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    trade_license VARCHAR(100) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Branches
CREATE TABLE IF NOT EXISTS branches (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) DEFAULT NULL,
    is_head_office TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- SOMFP monthly financial entries (per branch, per month)
CREATE TABLE IF NOT EXISTS somfp_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id INT UNSIGNED NOT NULL,
    period_year SMALLINT UNSIGNED NOT NULL,
    period_month TINYINT UNSIGNED NOT NULL,
    line_item_key VARCHAR(80) NOT NULL,
    amount DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_branch_period_item (branch_id, period_year, period_month, line_item_key),
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    INDEX idx_period (period_year, period_month)
) ENGINE=InnoDB;

-- SOMCI monthly comprehensive income entries (per branch, per month)
CREATE TABLE IF NOT EXISTS somci_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id INT UNSIGNED NOT NULL,
    period_year SMALLINT UNSIGNED NOT NULL,
    period_month TINYINT UNSIGNED NOT NULL,
    line_item_key VARCHAR(80) NOT NULL,
    amount DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_somci_branch_period_item (branch_id, period_year, period_month, line_item_key),
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    INDEX idx_somci_period (period_year, period_month)
) ENGINE=InnoDB;

-- Observations & recommendations per company (project)
CREATE TABLE IF NOT EXISTS company_observations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    head VARCHAR(255) NOT NULL,
    details TEXT DEFAULT NULL,
    risk TEXT DEFAULT NULL,
    recommendations TEXT DEFAULT NULL,
    status VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_company_observations_company (company_id)
) ENGINE=InnoDB;

-- Default admin user (password: admin123)
INSERT INTO users (full_name, email, password) VALUES
('System Administrator', 'admin@mata.ae', '$2y$10$hDynYwcdYZGsBv7sgjp.A.FLGrWif2qWAh/SWOgkKw.b1fAfsBt6O')
ON DUPLICATE KEY UPDATE full_name = full_name;

-- RBAC: Roles & Permissions
CREATE TABLE IF NOT EXISTS roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    perm_key VARCHAR(150) NOT NULL UNIQUE,
    label VARCHAR(150) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    can_read TINYINT(1) NOT NULL DEFAULT 0,
    can_write TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed base permissions
INSERT INTO permissions (perm_key, label, description) VALUES
('dashboard', 'Dashboard', 'Access dashboard'),
('companies', 'Companies', 'Manage companies and branches'),
('observations', 'Observations', 'Manage observations & recommendations'),
('linked_is', 'Linked IS', 'Manage linked income statement entries'),
('linked_bs', 'Linked BS', 'Manage linked balance sheet entries'),
('somfp', 'SOMFP', 'Manage SOMFP entries and reports'),
('somci', 'SOMCI', 'Manage SOMCI entries and reports'),
('sofp', 'SOFP', 'Access overall statement of financial position'),
('soci', 'SOCI', 'Access overall statement of comprehensive income'),
('glance', 'Glance', 'Access glance picture insights'),
('settings_users', 'Settings: Users', 'Manage users and role assignment'),
('settings_roles', 'Settings: Roles', 'Manage roles and permissions'),
('settings_logs', 'Settings: Logs', 'View activity and audit logs')
ON DUPLICATE KEY UPDATE label = VALUES(label);

-- Seed admin role and grant all permissions (read+write)
INSERT INTO roles (name, description)
VALUES ('Admin', 'Full access')
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), description = VALUES(description);
SET @admin_role_id = LAST_INSERT_ID();

INSERT IGNORE INTO role_permissions (role_id, permission_id, can_read, can_write)
SELECT @admin_role_id, p.id, 1, 1 FROM permissions p;

-- Assign Admin role to default admin user
INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, @admin_role_id FROM users u WHERE u.email = 'admin@mata.ae' AND u.deleted_at IS NULL;

-- Activity logs (audit trail)
CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    user_name VARCHAR(150) NULL,
    user_email VARCHAR(150) NULL,
    event_type VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    module_key VARCHAR(100) NULL,
    route_path VARCHAR(255) NOT NULL,
    request_method VARCHAR(10) NOT NULL,
    ip_address VARCHAR(64) NULL,
    user_agent VARCHAR(255) NULL,
    description VARCHAR(255) NULL,
    metadata_json JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_logs_user (user_id),
    INDEX idx_activity_logs_event (event_type),
    INDEX idx_activity_logs_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;
