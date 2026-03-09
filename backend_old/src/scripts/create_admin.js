const mysql = require('mysql2/promise');
const bcrypt = require('bcryptjs');
require('dotenv').config({ path: '../../.env' }); // Adjust path to reach .env in backend root

async function createAdmin() {
    const connection = await mysql.createConnection({
        host: process.env.DB_HOST || 'localhost',
        user: process.env.DB_USER || 'root',
        password: process.env.DB_PASSWORD || '',
        database: process.env.DB_NAME || 'osza_website'
    });

    const email = 'abdure@gmail.com';
    const password = 'password123'; // Default password for this user
    const hashedPassword = await bcrypt.hash(password, 10);

    try {
        const [rows] = await connection.execute('SELECT * FROM users WHERE email = ?', [email]);
        if (rows.length > 0) {
            console.log('User already exists');
            process.exit(0);
        }

        await connection.execute(
            'INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)',
            ['Abdure', email, hashedPassword, 'super_admin']
        );
        console.log(`Admin user created: ${email} / ${password}`);
    } catch (error) {
        console.error('Error creating admin:', error);
    } finally {
        await connection.end();
    }
}

createAdmin();
