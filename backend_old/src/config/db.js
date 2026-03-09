const mysql = require('mysql2');
try {
    require('dotenv').config();
} catch (e) { }

const pool = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    enableKeepAlive: true,
    keepAliveInitialDelay: 0
});

// Test connection on startup
pool.getConnection((err, connection) => {
    if (err) {
        console.error('CRITICAL: Database connection failed!');
        console.error(`- Error Code: ${err.code}`);
        console.error(`- Host: ${process.env.DB_HOST}`);
        console.error(`- User: ${process.env.DB_USER}`);
        console.error(`- Password provided: ${!!process.env.DB_PASSWORD}`);
    } else {
        console.log('Database connected successfully.');
        connection.release();
    }
});

module.exports = pool.promise();
