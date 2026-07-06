-- Soft delete migration for existing Mata MIS databases
USE matamis;

ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
ALTER TABLE companies ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
ALTER TABLE branches ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
ALTER TABLE somfp_entries ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
