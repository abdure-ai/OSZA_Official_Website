const db = require('../config/db');

// GET gallery items — public, supports ?woreda_id=&category=
exports.getGallery = async (req, res) => {
    try {
        const { woreda_id, category } = req.query;
        let sql = `
            SELECT g.*, w.name_en AS woreda_name, w.slug AS woreda_slug
            FROM gallery_items g
            LEFT JOIN woredas w ON g.woreda_id = w.id
            WHERE g.is_active = 1
        `;
        const params = [];
        if (woreda_id) { sql += ' AND g.woreda_id = ?'; params.push(woreda_id); }
        if (category) { sql += ' AND g.category = ?'; params.push(category); }
        sql += ' ORDER BY g.sort_order ASC, g.created_at DESC';

        const [rows] = await db.query(sql, params);
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET categories — returns distinct categories with cover image and count
exports.getCategories = async (req, res) => {
    try {
        const { woreda_id } = req.query;
        let sql = `
            SELECT
                g.category,
                COUNT(*) AS count,
                (SELECT gi.image_url FROM gallery_items gi
                 WHERE gi.category = g.category AND gi.is_active = 1
                 ${woreda_id ? 'AND gi.woreda_id = ?' : ''}
                 ORDER BY gi.sort_order ASC, gi.created_at DESC LIMIT 1) AS cover_url
            FROM gallery_items g
            WHERE g.is_active = 1
        `;
        const params = [];
        if (woreda_id) {
            params.push(woreda_id); // for subquery
            sql += ' AND g.woreda_id = ?';
            params.push(woreda_id); // for outer WHERE
        }
        sql += ' GROUP BY g.category ORDER BY g.category ASC';

        const [rows] = await db.query(sql, params);
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET all gallery items (admin — includes inactive)
exports.getAllGallery = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT g.*, w.name_en AS woreda_name
            FROM gallery_items g
            LEFT JOIN woredas w ON g.woreda_id = w.id
            ORDER BY g.created_at DESC
        `);
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// POST create gallery item (admin)
exports.createItem = async (req, res) => {
    try {
        const { title, category, woreda_id, sort_order, is_active } = req.body;
        if (!title || !category) {
            return res.status(400).json({ message: 'title and category are required.' });
        }
        if (!req.file) {
            return res.status(400).json({ message: 'An image file is required.' });
        }
        const image_url = `/uploads/${req.file.filename}`;

        const [result] = await db.query(
            `INSERT INTO gallery_items (title, image_url, category, woreda_id, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?)`,
            [
                title, image_url, category,
                woreda_id || null,
                sort_order || 0,
                is_active !== undefined ? (is_active ? 1 : 0) : 1,
            ]
        );
        res.status(201).json({ message: 'Gallery item created', id: result.insertId, image_url });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// PUT update gallery item (admin)
exports.updateItem = async (req, res) => {
    try {
        const { id } = req.params;
        const { title, category, woreda_id, sort_order, is_active } = req.body;
        const image_url = req.file ? `/uploads/${req.file.filename}` : undefined;

        const fields = { title, category, sort_order };
        if (woreda_id !== undefined) fields.woreda_id = woreda_id || null;
        if (is_active !== undefined) fields.is_active = is_active ? 1 : 0;
        if (image_url) fields.image_url = image_url;

        const entries = Object.entries(fields).filter(([, v]) => v !== undefined);
        if (entries.length === 0) return res.json({ message: 'Nothing to update' });

        const sql = `UPDATE gallery_items SET ${entries.map(([k]) => `${k}=?`).join(', ')} WHERE id=?`;
        await db.query(sql, [...entries.map(([, v]) => v), id]);
        res.json({ message: 'Gallery item updated' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// DELETE gallery item (admin)
exports.deleteItem = async (req, res) => {
    try {
        await db.query('DELETE FROM gallery_items WHERE id = ?', [req.params.id]);
        res.json({ message: 'Gallery item deleted' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};
