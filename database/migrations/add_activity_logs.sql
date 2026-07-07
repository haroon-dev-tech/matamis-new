-- Activity logs table (idempotent)
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

