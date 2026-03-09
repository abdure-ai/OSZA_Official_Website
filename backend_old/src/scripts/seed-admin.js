const mysql = require('mysql2/promise');
const bcrypt = require('bcryptjs');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../../.env') });

async function seedAdmin() {
    try {
        const connection = await mysql.createConnection({
            host: process.env.DB_HOST,
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD,
            database: process.env.DB_NAME
        });

        console.log('Connected to MySQL server.');

        const username = 'admin';
        const email = 'admin@osza.gov.et';
        const password = 'password123';
        const role = 'super_admin';

        // Check if admin exists
        const [rows] = await connection.execute('SELECT * FROM users WHERE email = ?', [email]);
        if (rows.length > 0) {
            console.log('Admin user already exists.');
            await connection.end();
            return;
        }

        const hashedPassword = await bcrypt.hash(password, 10);

        await connection.execute(
            'INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)',
            [username, email, hashedPassword, role]
        );

        console.log(`Admin user created successfully.\nEmail: ${email}\nPassword: ${password}`);
        await connection.end();
    } catch (err) {
        console.error('Error seeding admin:', err);
        process.exit(1);
    }
}

seedAdmin();
