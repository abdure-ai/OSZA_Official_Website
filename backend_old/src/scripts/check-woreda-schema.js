const mysql = require('mysql2/promise');
require('dotenv').config();

async function check() {
    const connection = await mysql.createConnection({
        host: process.env.DB_HOST || 'localhost',
        user: process.env.DB_USER || 'root',
        password: process.env.DB_PASSWORD || '',
        database: process.env.DB_NAME || 'osza_website',
    });

    try {
        const [rows] = await connection.query('DESCRIBE woredas');
        console.log(rows.map(r => r.Field).join(', '));
    } catch (error) {
        console.error(error);
    } finally {
        await connection.end();
    }
}

check();
