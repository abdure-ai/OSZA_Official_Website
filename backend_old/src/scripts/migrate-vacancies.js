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
        console.log('--- Phase 13 Migration Started ---');

        // 1. Vacancies Table
        console.log('Creating vacancies table...');
        await connection.query(`
            CREATE TABLE IF NOT EXISTS vacancies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title_en VARCHAR(255) NOT NULL,
                title_am VARCHAR(255),
                title_or VARCHAR(255),
                description_en TEXT NOT NULL,
                description_am TEXT,
                description_or TEXT,
                requirements_en TEXT,
                requirements_am TEXT,
                requirements_or TEXT,
                department VARCHAR(100) NOT NULL,
                vacancy_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') DEFAULT 'Full-time',
                location_en VARCHAR(255) DEFAULT 'Kemise',
                deadline DATETIME NOT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);
        console.log('✅ Vacancies table created.');

        // 2. Tenders Table
        console.log('Creating tenders table...');
        await connection.query(`
            CREATE TABLE IF NOT EXISTS tenders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title_en VARCHAR(255) NOT NULL,
                title_am VARCHAR(255),
                title_or VARCHAR(255),
                description_en TEXT,
                description_am TEXT,
                description_or TEXT,
                ref_number VARCHAR(100) UNIQUE,
                deadline DATETIME NOT NULL,
                file_url VARCHAR(1024),
                status ENUM('Open', 'Closed', 'Awarded', 'Cancelled') DEFAULT 'Open',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);
        console.log('✅ Tenders table created.');

        // 3. Seed Sample Data
        console.log('Seeding sample data...');

        // Vacancies
        const [existingVacancies] = await connection.query('SELECT COUNT(*) as count FROM vacancies');
        if (existingVacancies[0].count === 0) {
            await connection.query(`
                INSERT INTO vacancies (title_en, description_en, department, vacancy_type, deadline) VALUES
                ('Senior Accountant', 'We are looking for an experienced accountant to join our Finance Team.', 'Finance', 'Full-time', DATE_ADD(NOW(), INTERVAL 14 DAY)),
                ('IT Support Technician', 'Provide technical support to administration staff.', 'Information Technology', 'Contract', DATE_ADD(NOW(), INTERVAL 7 DAY)),
                ('Administrative Assistant', 'General office administration and support.', 'General Secretariat', 'Full-time', DATE_ADD(NOW(), INTERVAL 10 DAY))
            `);
            console.log('✅ Seeded sample vacancies.');
        }

        // Tenders
        const [existingTenders] = await connection.query('SELECT COUNT(*) as count FROM tenders');
        if (existingTenders[0].count === 0) {
            await connection.query(`
                INSERT INTO tenders (title_en, ref_number, deadline, status) VALUES
                ('Supply of Office Equipment 2026', 'OSZA/TEN/2026/01', DATE_ADD(NOW(), INTERVAL 30 DAY), 'Open'),
                ('Construction of New Health Center', 'OSZA/TEN/2026/02', DATE_ADD(NOW(), INTERVAL 45 DAY), 'Open'),
                ('Security Services for Zone HQ', 'OSZA/TEN/2026/03', DATE_ADD(NOW(), INTERVAL 20 DAY), 'Open')
            `);
            console.log('✅ Seeded sample tenders.');
        }

        console.log('--- Migration Completed Successfully ---');
    } catch (error) {
        console.error('❌ Migration failed:', error);
    } finally {
        if (connection) connection.release();
        await pool.end();
    }
}

migrate();
