const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Get all investment opportunities
exports.getAllInvestments = async (req, res) => {
    try {
        const { category, status } = req.query;
        let query = 'SELECT * FROM investments WHERE 1=1';
        let params = [];

        if (category && category !== 'All') {
            query += ' AND category = ?';
            params.push(category);
        }
        if (status) {
            query += ' AND status = ?';
            params.push(status);
        }

        query += ' ORDER BY created_at DESC';

        const [investments] = await db.query(query, params);
        res.json(investments);
    } catch (error) {
        console.error('Error fetching investments:', error);
        res.status(500).json({ message: 'Error fetching investments' });
    }
};

// Create investment opportunity
exports.createInvestment = async (req, res) => {
    try {
        const {
            title_en, description_en, category,
            location, budget, incentives_en,
            contact_name, contact_phone, contact_email, status
        } = req.body;

        const thumbnail_url = req.file ? `/uploads/investments/${req.file.filename}` : null;

        const [result] = await db.query(
            `INSERT INTO investments (
                title_en, description_en, category, 
                location, budget, incentives_en,
                contact_name, contact_phone, contact_email, 
                thumbnail_url, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                title_en, description_en || null, category || null,
                location || null, budget || null, incentives_en || null,
                contact_name || null, contact_phone || null, contact_email || null,
                thumbnail_url, status || 'Open'
            ]
        );

        res.status(201).json({ id: result.insertId, message: 'Investment created successfully' });
    } catch (error) {
        console.error('Error creating investment:', error);
        res.status(500).json({ message: 'Error creating investment' });
    }
};

// Update investment opportunity
exports.updateInvestment = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            title_en, description_en, category,
            location, budget, incentives_en,
            contact_name, contact_phone, contact_email, status
        } = req.body;

        const [current] = await db.query('SELECT thumbnail_url FROM investments WHERE id = ?', [id]);
        if (current.length === 0) return res.status(404).json({ message: 'Investment not found' });

        let thumbnail_url = current[0].thumbnail_url;
        if (req.file) {
            if (thumbnail_url) {
                const oldPath = path.join(__dirname, '../../', thumbnail_url);
                if (fs.existsSync(oldPath)) fs.unlinkSync(oldPath);
            }
            thumbnail_url = `/uploads/investments/${req.file.filename}`;
        }

        await db.query(
            `UPDATE investments SET 
                title_en=?, description_en=?, category=?, 
                location=?, budget=?, incentives_en=?,
                contact_name=?, contact_phone=?, contact_email=?, 
                thumbnail_url=?, status=?
            WHERE id = ?`,
            [
                title_en, description_en, category,
                location, budget, incentives_en,
                contact_name, contact_phone, contact_email,
                thumbnail_url, status, id
            ]
        );

        res.json({ message: 'Investment updated successfully' });
    } catch (error) {
        console.error('Error updating investment:', error);
        res.status(500).json({ message: 'Error updating investment' });
    }
};

// Delete investment opportunity
exports.deleteInvestment = async (req, res) => {
    try {
        const { id } = req.params;
        const [investment] = await db.query('SELECT thumbnail_url FROM investments WHERE id = ?', [id]);

        if (investment.length > 0 && investment[0].thumbnail_url) {
            const thumbPath = path.join(__dirname, '../../', investment[0].thumbnail_url);
            if (fs.existsSync(thumbPath)) fs.unlinkSync(thumbPath);
        }

        await db.query('DELETE FROM investments WHERE id = ?', [id]);
        res.json({ message: 'Investment deleted successfully' });
    } catch (error) {
        console.error('Error deleting investment:', error);
        res.status(500).json({ message: 'Error deleting investment' });
    }
};
