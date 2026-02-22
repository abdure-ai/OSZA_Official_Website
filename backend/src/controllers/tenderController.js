const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Get all tenders
exports.getAllTenders = async (req, res) => {
    try {
        const { status } = req.query;
        let query = 'SELECT * FROM tenders WHERE 1=1';
        const params = [];

        if (status) {
            query += ' AND status = ?';
            params.push(status);
        }

        query += ' ORDER BY deadline DESC';

        const [rows] = await db.query(query, params);
        res.json(rows);
    } catch (error) {
        console.error('Error fetching tenders:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Create tender (Admin)
exports.createTender = async (req, res) => {
    try {
        const {
            title_en, title_am, title_or,
            description_en, description_am, description_or,
            ref_number, deadline
        } = req.body;

        const file_url = req.file ? `/uploads/${req.file.filename}` : null;

        const [result] = await db.query(
            `INSERT INTO tenders (
                title_en, title_am, title_or,
                description_en, description_am, description_or,
                ref_number, deadline, file_url
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                title_en, title_am, title_or,
                description_en, description_am, description_or,
                ref_number, deadline, file_url
            ]
        );

        res.status(201).json({
            message: 'Tender created successfully',
            tenderId: result.insertId
        });
    } catch (error) {
        console.error('Error creating tender:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Update tender (Admin)
exports.updateTender = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            title_en, title_am, title_or,
            description_en, description_am, description_or,
            ref_number, deadline, status
        } = req.body;

        let query = `UPDATE tenders SET 
            title_en = ?, title_am = ?, title_or = ?,
            description_en = ?, description_am = ?, description_or = ?,
            ref_number = ?, deadline = ?, status = ?`;
        const params = [
            title_en, title_am, title_or,
            description_en, description_am, description_or,
            ref_number, deadline, status
        ];

        if (req.file) {
            // Delete old file if exists
            const [old] = await db.query('SELECT file_url FROM tenders WHERE id = ?', [id]);
            if (old.length > 0 && old[0].file_url) {
                const oldPath = path.join(__dirname, '../../', old[0].file_url);
                if (fs.existsSync(oldPath)) fs.unlinkSync(oldPath);
            }
            query += ', file_url = ?';
            params.push(`/uploads/${req.file.filename}`);
        }

        query += ' WHERE id = ?';
        params.push(id);

        await db.query(query, params);
        res.json({ message: 'Tender updated successfully' });
    } catch (error) {
        console.error('Error updating tender:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Delete tender (Admin)
exports.deleteTender = async (req, res) => {
    try {
        const { id } = req.params;

        // Get file path first
        const [rows] = await db.query('SELECT file_url FROM tenders WHERE id = ?', [id]);
        if (rows.length > 0 && rows[0].file_url) {
            const filePath = path.join(__dirname, '../../', rows[0].file_url);
            if (fs.existsSync(filePath)) fs.unlinkSync(filePath);
        }

        await db.query('DELETE FROM tenders WHERE id = ?', [id]);
        res.json({ message: 'Tender deleted successfully' });
    } catch (error) {
        console.error('Error deleting tender:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};
