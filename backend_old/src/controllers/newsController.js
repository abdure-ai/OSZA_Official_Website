const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Helper to delete image from disk
const deleteFile = (fileUrl) => {
    if (!fileUrl) return;
    const filePath = path.join(__dirname, '../../', fileUrl);
    if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
    }
};

// Get all news (with optional category filter)
// Admin mode: returns all statuses. Public mode: only published.
exports.getAllNews = async (req, res) => {
    try {
        const { category, admin } = req.query;
        let query = admin === 'true'
            ? 'SELECT * FROM posts WHERE 1=1'
            : 'SELECT * FROM posts WHERE status = "published"';
        const params = [];

        if (category && category !== 'All News') {
            query += ' AND category = ?';
            params.push(category);
        }

        query += ' ORDER BY created_at DESC';

        const [rows] = await db.query(query, params);
        res.json(rows);
    } catch (error) {
        console.error('Error fetching news:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Get single news by ID
exports.getNewsById = async (req, res) => {
    try {
        const { id } = req.params;
        const [rows] = await db.query('SELECT * FROM posts WHERE id = ?', [id]);

        if (rows.length === 0) {
            return res.status(404).json({ message: 'News not found' });
        }

        res.json(rows[0]);
    } catch (error) {
        console.error('Error fetching news details:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Create News (Admin)
exports.createNews = async (req, res) => {
    try {
        const { title_en, title_am, title_or, content_en, content_am, content_or, category, status, published_at } = req.body;

        if (!title_en || !content_en || !category) {
            return res.status(400).json({ message: 'title_en, content_en, and category are required.' });
        }

        const thumbnail_url = req.file ? `/uploads/${req.file.filename}` : null;
        const adminId = req.user?.id || null;
        const pubDate = status === 'published' ? (published_at || new Date()) : null;

        const [result] = await db.query(
            `INSERT INTO posts (title_en, title_am, title_or, content_en, content_am, content_or, category, status, thumbnail_url, admin_id, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [title_en, title_am || null, title_or || null, content_en, content_am || null, content_or || null, category, status || 'draft', thumbnail_url, adminId, pubDate]
        );

        res.status(201).json({ message: 'Post created successfully', id: result.insertId });
    } catch (error) {
        console.error('Error creating news:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Update News (Admin)
exports.updateNews = async (req, res) => {
    try {
        const { id } = req.params;
        const { title_en, title_am, title_or, content_en, content_am, content_or, category, status } = req.body;

        const [existing] = await db.query('SELECT id, thumbnail_url FROM posts WHERE id = ?', [id]);
        if (existing.length === 0) {
            return res.status(404).json({ message: 'News post not found' });
        }

        let thumbnail_url = existing[0].thumbnail_url;
        if (req.file) {
            deleteFile(thumbnail_url);
            thumbnail_url = `/uploads/${req.file.filename}`;
        }

        const pubDate = status === 'published' ? new Date() : null;

        await db.query(
            `UPDATE posts SET title_en = ?, title_am = ?, title_or = ?, content_en = ?, content_am = ?, content_or = ?,
             category = ?, status = ?, thumbnail_url = ?, published_at = COALESCE(?, published_at)
             WHERE id = ?`,
            [title_en, title_am || null, title_or || null, content_en, content_am || null, content_or || null, category, status, thumbnail_url, pubDate, id]
        );

        res.json({ message: 'Post updated successfully' });
    } catch (error) {
        console.error('Error updating news:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Delete News (Admin)
exports.deleteNews = async (req, res) => {
    try {
        const { id } = req.params;

        const [existing] = await db.query('SELECT id, thumbnail_url FROM posts WHERE id = ?', [id]);
        if (existing.length === 0) {
            return res.status(404).json({ message: 'News post not found' });
        }

        deleteFile(existing[0].thumbnail_url);
        await db.query('DELETE FROM posts WHERE id = ?', [id]);
        res.json({ message: 'Post deleted successfully' });
    } catch (error) {
        console.error('Error deleting news:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};
