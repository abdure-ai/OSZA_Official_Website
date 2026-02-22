const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Get all directory contacts
exports.getAllContacts = async (req, res) => {
    try {
        const { category } = req.query;
        let query = 'SELECT * FROM directory';
        let params = [];

        if (category && category !== 'All') {
            query += ' WHERE category = ?';
            params.push(category);
        }

        query += ' ORDER BY sort_order ASC, name_en ASC';

        const [contacts] = await db.query(query, params);
        res.json(contacts);
    } catch (error) {
        console.error('Error fetching contacts:', error);
        res.status(500).json({ message: 'Error fetching contacts' });
    }
};

// Create new contact
exports.createContact = async (req, res) => {
    try {
        const {
            name_en, name_am, name_or,
            position_en, position_am, position_or,
            department_en, phone, email, office_location,
            category, sort_order
        } = req.body;

        const photo_url = req.file ? `/uploads/directory/${req.file.filename}` : null;

        const [result] = await db.query(
            `INSERT INTO directory (
                name_en, name_am, name_or, 
                position_en, position_am, position_or,
                department_en, phone, email, office_location,
                photo_url, category, sort_order
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                name_en, name_am || null, name_or || null,
                position_en, position_am || null, position_or || null,
                department_en || null, phone || null, email || null, office_location || null,
                photo_url, category || 'General', sort_order || 0
            ]
        );

        res.status(201).json({ id: result.insertId, message: 'Contact created successfully' });
    } catch (error) {
        console.error('Error creating contact:', error);
        res.status(500).json({ message: 'Error creating contact' });
    }
};

// Update contact
exports.updateContact = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            name_en, name_am, name_or,
            position_en, position_am, position_or,
            department_en, phone, email, office_location,
            category, sort_order
        } = req.body;

        const [current] = await db.query('SELECT photo_url FROM directory WHERE id = ?', [id]);
        if (current.length === 0) return res.status(404).json({ message: 'Contact not found' });

        let photo_url = current[0].photo_url;
        if (req.file) {
            // Delete old photo if exists
            if (photo_url) {
                const oldPath = path.join(__dirname, '../../', photo_url);
                if (fs.existsSync(oldPath)) fs.unlinkSync(oldPath);
            }
            photo_url = `/uploads/directory/${req.file.filename}`;
        }

        await db.query(
            `UPDATE directory SET 
                name_en=?, name_am=?, name_or=?, 
                position_en=?, position_am=?, position_or=?,
                department_en=?, phone=?, email=?, office_location=?,
                photo_url=?, category=?, sort_order=?
            WHERE id = ?`,
            [
                name_en, name_am, name_or,
                position_en, position_am, position_or,
                department_en, phone, email, office_location,
                photo_url, category, sort_order, id
            ]
        );

        res.json({ message: 'Contact updated successfully' });
    } catch (error) {
        console.error('Error updating contact:', error);
        res.status(500).json({ message: 'Error updating contact' });
    }
};

// Delete contact
exports.deleteContact = async (req, res) => {
    try {
        const { id } = req.params;
        const [contact] = await db.query('SELECT photo_url FROM directory WHERE id = ?', [id]);

        if (contact.length > 0 && contact[0].photo_url) {
            const photoPath = path.join(__dirname, '../../', contact[0].photo_url);
            if (fs.existsSync(photoPath)) fs.unlinkSync(photoPath);
        }

        await db.query('DELETE FROM directory WHERE id = ?', [id]);
        res.json({ message: 'Contact deleted successfully' });
    } catch (error) {
        console.error('Error deleting contact:', error);
        res.status(500).json({ message: 'Error deleting contact' });
    }
};
