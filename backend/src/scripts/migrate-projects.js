const mysql = require('mysql2/promise');
require('dotenv').config();

const pool = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'osza_website',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

async function migrate() {
    let connection;
    try {
        connection = await pool.getConnection();
        console.log('--- Phase 14 Migration Started ---');

        // 1. Projects Table
        console.log('Creating projects table...');
        await connection.query(`
            CREATE TABLE IF NOT EXISTS projects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title_en VARCHAR(255) NOT NULL,
                title_am VARCHAR(255),
                title_or VARCHAR(255),
                description_en TEXT,
                description_am TEXT,
                description_or TEXT,
                location_en VARCHAR(255),
                start_date DATE NOT NULL,
                end_date DATE,
                status ENUM('Planning', 'In Progress', 'On Hold', 'Completed', 'Cancelled') DEFAULT 'Planning',
                budget DECIMAL(18, 2),
                budget_currency VARCHAR(10) DEFAULT 'ETB',
                progress INT DEFAULT 0 COMMENT 'Percentage 0-100',
                contractor VARCHAR(255),
                funding_source VARCHAR(255),
                is_published BOOLEAN DEFAULT TRUE,
                cover_image_url VARCHAR(1024),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);
        console.log('✅ Projects table created.');

        // 2. Admin Message Table
        console.log('Creating admin_message table...');
        await connection.query(`
            CREATE TABLE IF NOT EXISTS admin_message (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL DEFAULT 'Zone Administrator',
                title_position VARCHAR(255) NOT NULL DEFAULT 'Chief Administrator, Oromo Special Zone',
                message_en TEXT NOT NULL,
                photo_url VARCHAR(1024),
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);
        console.log('✅ Admin message table created.');

        // 3. Seed Sample Data
        console.log('Seeding sample data...');

        // Projects
        const [existingProjects] = await connection.query('SELECT COUNT(*) as count FROM projects');
        if (existingProjects[0].count === 0) {
            await connection.query(`
                INSERT INTO projects (title_en, description_en, location_en, start_date, end_date, status, budget, progress, contractor, funding_source) VALUES
                ('Road Rehabilitation – Kemise-Bati Highway', 'Rehabilitation of the 58km Kemise-Bati Highway to improve connectivity between woredas.', 'Kemise to Bati', '2025-01-15', '2026-06-30', 'In Progress', 125000000.00, 65, 'Afar Construction Co.', 'Federal Budget'),
                ('New Zone Health Center – Kemise', 'Construction of a modern primary health center to serve 50,000 residents in the zone capital.', 'Kemise Town', '2025-03-01', '2026-03-01', 'In Progress', 35000000.00, 40, 'Oromia Builders Ltd.', 'Regional Budget'),
                ('Solar Power Grid – Rural Woredas', 'Installation of off-grid solar power systems across 8 rural woredas.', 'Multiple Woredas', '2024-07-01', '2025-12-31', 'Completed', 52000000.00, 100, 'GreenEnergy Ethiopia', 'World Bank Grant'),
                ('Zone Administrative Building Renovation', 'Renovation of the main administrative building including IT infrastructure upgrade.', 'Zone HQ, Kemise', '2026-01-10', '2026-08-30', 'Planning', 8500000.00, 5, 'TBD', 'Zone Own Revenue')
            `);
            console.log('✅ Seeded sample projects.');
        }

        // Admin Message
        const [existingMsg] = await connection.query('SELECT COUNT(*) as count FROM admin_message');
        if (existingMsg[0].count === 0) {
            await connection.query(`
                INSERT INTO admin_message (name, title_position, message_en) VALUES
                ('Zone Administrator', 'Chief Administrator, Oromo Special Zone', 
                'We are committed to serving the people of the Oromo Special Zone with integrity, transparency, and accountability. This digital platform represents a major step in making our administration accessible, open, and responsive to every citizen. Together, we are building a prosperous future for our communities.')
            `);
            console.log('✅ Seeded admin message.');
        }

        console.log('--- Phase 14 Migration Completed Successfully ---');
    } catch (error) {
        console.error('❌ Migration failed:', error);
    } finally {
        if (connection) connection.release();
        await pool.end();
    }
}

migrate();
