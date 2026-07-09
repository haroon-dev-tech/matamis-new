-- RBAC tables + seed data (idempotent)

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

INSERT INTO roles (name, description)
VALUES ('Admin', 'Full access')
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), description = VALUES(description);
SET @admin_role_id = LAST_INSERT_ID();

INSERT IGNORE INTO role_permissions (role_id, permission_id, can_read, can_write)
SELECT @admin_role_id, p.id, 1, 1 FROM permissions p;

INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, @admin_role_id FROM users u WHERE u.email = 'admin@mata.ae' AND u.deleted_at IS NULL;

