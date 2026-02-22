const db = require('../config/db');

// GET /api/stats - Returns counts for the admin dashboard
exports.getStats = async (req, res) => {
    try {
        const [[newsResult], [docsResult], [alertsResult], [usersResult], [vacanciesResult], [tendersResult]] = await Promise.all([
            db.query('SELECT COUNT(*) AS count FROM posts'),
            db.query('SELECT COUNT(*) AS count FROM documents'),
            db.query('SELECT COUNT(*) AS count FROM emergency_alerts WHERE is_active = 1'),
            db.query('SELECT COUNT(*) AS count FROM users'),
            db.query('SELECT COUNT(*) AS count FROM vacancies WHERE is_active = 1'),
            db.query('SELECT COUNT(*) AS count FROM tenders WHERE status = "Open"'),
        ]);

        res.json({
            news: newsResult[0].count,
            documents: docsResult[0].count,
            alerts: alertsResult[0].count,
            users: usersResult[0].count,
            vacancies: vacanciesResult[0].count,
            tenders: tendersResult[0].count,
        });
    } catch (error) {
        console.error('Error fetching stats:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};
