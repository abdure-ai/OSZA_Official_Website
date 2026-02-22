const path = require('path');
// Load .env from the same place db.js does
require('dotenv').config({ path: path.join(__dirname, '../../.env') });
const db = require('../config/db');

async function migrate() {
    try {
        await db.query(`
            CREATE TABLE IF NOT EXISTS hero_slides (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title_en VARCHAR(255),
                subtitle_en VARCHAR(255),
                media_url VARCHAR(1024),
                media_type ENUM('image','video') DEFAULT 'image',
                cta_text VARCHAR(255),
                cta_url VARCHAR(1024),
                sort_order INT DEFAULT 0,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);
        console.log('✅  hero_slides table created (or already exists).');
    } catch (err) {
        console.error('❌  Migration failed:', err.message);
    } finally {
        process.exit(0);
    }
}

migrate();
