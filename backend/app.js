const http = require('http');

const server = http.createServer((req, res) => {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    const response = {
        status: 'ok',
        message: 'PASSENGER IS ACTIVE - v1.5 Standalone',
        time: new Date().toISOString(),
        url: req.url,
        env: {
            NODE_ENV: process.env.NODE_ENV,
            DB_USER: process.env.DB_USER,
            DB_NAME: process.env.DB_NAME,
            has_password: !!process.env.DB_PASSWORD,
            pass_length: process.env.DB_PASSWORD ? process.env.DB_PASSWORD.length : 0
        },
        cwd: process.cwd()
    };
    res.end(JSON.stringify(response, null, 2));
});

console.log('Standalone diagnostic server starting...');
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log('Listening on port ' + PORT);
});

module.exports = server;
