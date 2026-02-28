const express = require('express');
const app = express();

// ABSOLUTE TOP PRIORITY DIAGNOSTIC
app.get('/api/passenger-check', (req, res) => {
    res.json({
        status: 'ok',
        message: 'Passenger is successfully routing to app.js',
        time: new Date().toISOString(),
        node_version: process.version,
        cwd: process.cwd(),
        env: {
            NODE_ENV: process.env.NODE_ENV,
            DB_USER: process.env.DB_USER,
            DB_NAME: process.env.DB_NAME,
            has_password: !!process.env.DB_PASSWORD,
            pass_length: process.env.DB_PASSWORD ? process.env.DB_PASSWORD.length : 0
        }
    });
});

app.get('/', (req, res) => {
    res.send(`<h1>OSZA Backend Diagnostic</h1><p>Status: Healthy (v1.3)</p><p>Time: ${new Date().toISOString()}</p>`);
});

// Try to load the main server logic
try {
    const mainApp = require('./src/server');
    app.use(mainApp);
    console.log('Successfully loaded src/server.js');
} catch (err) {
    console.error('Failed to load src/server.js:', err);
    app.get('/api/load-error', (req, res) => res.status(500).json({ error: err.message, stack: err.stack }));
}

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`Diagnostic server listening on port ${PORT}`);
});

module.exports = app;
