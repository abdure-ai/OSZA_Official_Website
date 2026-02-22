const mysql = require('mysql2/promise');
const fs = require('fs');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../../.env') });

const schemaPath = path.join(__dirname, '../../db/schema.sql');

async function initDb() {
    try {
        // Connect to MySQL server (without selecting database yet)
        const connection = await mysql.createConnection({
            host: process.env.DB_HOST,
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD,
            multipleStatements: true
        });

        console.log('Connected to MySQL server.');

        // Read schema file
        const schema = fs.readFileSync(schemaPath, 'utf8');

        // Execute schema
        console.log('Executing schema...');
        await connection.query(schema);

        console.log('Database initialized successfully.');
        await connection.end();
    } catch (err) {
        console.error('Error initializing database:', err);
        process.exit(1);
    }
}

initDb();
