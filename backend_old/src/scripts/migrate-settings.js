const mysql = require('mysql2/promise');
require('dotenv').config();

async function migrate() {
    const connection = await mysql.createConnection({
        host: process.env.DB_HOST || 'localhost',
        user: process.env.DB_USER || 'root',
        password: process.env.DB_PASSWORD || '',
        database: process.env.DB_NAME || 'osza_website',
    });

    console.log('Starting migration: office_settings table');

    try {
        await connection.query(`
            CREATE TABLE IF NOT EXISTS office_settings (
                id INT PRIMARY KEY DEFAULT 1,
                phone VARCHAR(50),
                email VARCHAR(255),
                address TEXT,
                working_hours TEXT,
                map_url TEXT,
                facebook_url TEXT,
                twitter_url TEXT,
                linkedin_url TEXT,
                youtube_url TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);

        // Insert default settings if not exists
        const [rows] = await connection.query('SELECT * FROM office_settings WHERE id = 1');
        if (rows.length === 0) {
            await connection.query(`
                INSERT INTO office_settings (id, phone, email, address, working_hours)
                VALUES (1, '+251 114 654 321', 'info@osza.gov.et', 'Oromo Special Zone, Ethiopia', 'Mon - Fri: 8:30 AM - 5:30 PM')
            `);
            console.log('Default office settings inserted');
        }

        console.log('Table office_settings ready');
    } catch (error) {
        console.error('Migration failed:', error);
    } finally {
        await connection.end();
    }
}

migrate();
