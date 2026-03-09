const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// GET /api/admin-message - Public: returns the active message
exports.getMessage = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM admin_message WHERE is_active = 1 ORDER BY id DESC LIMIT 1');
        if (!rows.length) return res.json(null);
        res.json(rows[0]);
    } catch (error) {
        console.error('Error fetching admin message:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

// PUT /api/admin-message (Admin)
exports.updateMessage = async (req, res) => {
    try {
        const { name, title_position, message_en, message_am, message_or, is_active } = req.body;

        // Check if a record exists
        const [rows] = await db.query('SELECT * FROM admin_message ORDER BY id LIMIT 1');

        let photo_url = rows.length ? rows[0].photo_url : null;

        // Handle photo upload
        if (req.file) {
            // Delete old photo if it exists
            if (photo_url) {
                const oldPath = path.join(__dirname, '../../uploads', path.basename(photo_url));
                if (fs.existsSync(oldPath)) fs.unlinkSync(oldPath);
            }
            photo_url = `/uploads/${req.file.filename}`;
        }

        // Convert string boolean to integer for MySQL
        const isActive = is_active !== undefined ? (is_active === 'true' || is_active === true || is_active === '1' ? 1 : 0) : undefined;

        if (rows.length) {
            await db.query(
                'UPDATE admin_message SET name=?, title_position=?, message_en=?, message_am=?, message_or=?, photo_url=?, is_active=? WHERE id=?',
                [name, title_position, message_en, message_am, message_or, photo_url, isActive, rows[0].id]
            );
        } else {
            await db.query(
                'INSERT INTO admin_message (name, title_position, message_en, message_am, message_or, photo_url, is_active) VALUES (?,?,?,?,?,?,?)',
                [name, title_position, message_en, message_am, message_or, photo_url, isActive]
            );
        }
        res.json({ message: 'Admin message updated' });
    } catch (error) {
        console.error('Error updating admin message:', error);
        res.status(500).json({ message: 'Server error' });
    }
};
