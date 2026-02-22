const db = require('../config/db');

// GET all active alerts (public)
exports.getActiveAlerts = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT * FROM emergency_alerts WHERE is_active = 1 AND (expires_at IS NULL OR expires_at > NOW()) ORDER BY created_at DESC'
        );
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET all alerts (admin)
exports.getAllAlerts = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM emergency_alerts ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// POST create alert (admin)
exports.createAlert = async (req, res) => {
    try {
        const { message_en, message_am, message_or, severity, is_active, expires_at } = req.body;
        if (!message_en || !severity) {
            return res.status(400).json({ message: 'message_en and severity are required.' });
        }
        const [result] = await db.query(
            'INSERT INTO emergency_alerts (message_en, message_am, message_or, severity, is_active, expires_at) VALUES (?, ?, ?, ?, ?, ?)',
            [message_en, message_am || null, message_or || null, severity, is_active ? 1 : 0, expires_at || null]
        );
        res.status(201).json({ message: 'Alert created', id: result.insertId });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// PUT toggle is_active (admin)
exports.toggleAlert = async (req, res) => {
    try {
        const { id } = req.params;
        await db.query('UPDATE emergency_alerts SET is_active = NOT is_active WHERE id = ?', [id]);
        res.json({ message: 'Alert toggled' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// DELETE alert (admin)
exports.deleteAlert = async (req, res) => {
    try {
        const { id } = req.params;
        await db.query('DELETE FROM emergency_alerts WHERE id = ?', [id]);
        res.json({ message: 'Alert deleted' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};
