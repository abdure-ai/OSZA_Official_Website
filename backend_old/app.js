/**
 * OSZA Backend - Production Entry Point for cPanel
 */
try {
    require('dotenv').config();
} catch (e) {
    console.log('dotenv skip (using system env)');
}

const app = require('./src/server');
const PORT = process.env.PORT || 5000;

// Log startup info
console.log(`Starting OSZA Backend...`);
console.log(`NODE_ENV: ${process.env.NODE_ENV || 'development'}`);
console.log(`DB_NAME: ${process.env.DB_NAME}`);

const server = app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});

// Handle graceful shutdown
process.on('SIGTERM', () => {
    server.close(() => {
        console.log('Process terminated');
    });
});

module.exports = app;
