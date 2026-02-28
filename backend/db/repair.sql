-- REPAIR SCRIPT FOR CPANEL DATABASE
-- 1. Select your database (likely 'endrisus_osza') in phpMyAdmin.
-- 2. Go to the SQL tab and run these commands one by one or together.

-- --- Repair 'admin_message' table ---
-- This ensures 'is_active' and other columns exist to prevent 500 Errors
CREATE TABLE IF NOT EXISTS admin_message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL DEFAULT 'Zone Administrator',
    title_position VARCHAR(255) NOT NULL DEFAULT 'Chief Administrator, Oromo Special Zone',
    message_en TEXT NOT NULL,
    photo_url VARCHAR(1024),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE admin_message ADD COLUMN IF NOT EXISTS message_am TEXT AFTER message_en;
ALTER TABLE admin_message ADD COLUMN IF NOT EXISTS message_or TEXT AFTER message_am;
ALTER TABLE admin_message ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE;

-- --- Repair 'projects' table ---
-- Ensure all columns match the current API requirements
ALTER TABLE projects ADD COLUMN IF NOT EXISTS title_am VARCHAR(255) AFTER title_en;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS title_or VARCHAR(255) AFTER title_am;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS description_am TEXT AFTER description_en;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS description_or TEXT AFTER description_am;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS is_published BOOLEAN DEFAULT TRUE AFTER funding_source;

-- --- Repair 'tenders' table ---
ALTER TABLE tenders ADD COLUMN IF NOT EXISTS title_am VARCHAR(255) AFTER title_en;
ALTER TABLE tenders ADD COLUMN IF NOT EXISTS title_or VARCHAR(255) AFTER title_am;
ALTER TABLE tenders ADD COLUMN IF NOT EXISTS description_am TEXT AFTER description_en;
ALTER TABLE tenders ADD COLUMN IF NOT EXISTS description_or TEXT AFTER description_am;
ALTER TABLE tenders ADD COLUMN IF NOT EXISTS ref_number VARCHAR(100) AFTER description_or;

-- --- Seed initial data if empty ---
INSERT INTO admin_message (name, title_position, message_en, is_active)
SELECT 'Zone Administrator', 'Chief Administrator, Oromo Special Zone', 'Welcome to the official portal.', 1
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM admin_message);
