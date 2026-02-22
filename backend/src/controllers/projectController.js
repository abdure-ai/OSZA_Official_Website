const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// GET /api/projects - Public listing with optional status filter
exports.getAllProjects = async (req, res) => {
    try {
        const { status } = req.query;
        let query = 'SELECT * FROM projects WHERE is_published = 1';
        const params = [];
        if (status) {
            query += ' AND status = ?';
            params.push(status);
        }
        query += ' ORDER BY start_date DESC';
        const [rows] = await db.query(query, params);
        res.json(rows);
    } catch (error) {
        console.error('Error fetching projects:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

// GET /api/projects/all - Admin: get all including unpublished
exports.getAllProjectsAdmin = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM projects ORDER BY start_date DESC');
        res.json(rows);
    } catch (error) {
        console.error('Error fetching all projects:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

// GET /api/projects/:id
exports.getProjectById = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM projects WHERE id = ?', [req.params.id]);
        if (!rows.length) return res.status(404).json({ message: 'Project not found' });
        res.json(rows[0]);
    } catch (error) {
        res.status(500).json({ message: 'Server error' });
    }
};

// POST /api/projects (Admin)
exports.createProject = async (req, res) => {
    try {
        const { title_en, description_en, location_en, start_date, end_date, status, budget, progress, contractor, funding_source, is_published } = req.body;

        // Convert string boolean to integer for MySQL
        const isPublished = is_published === 'true' || is_published === true || is_published === '1' ? 1 : 0;

        const cover_image_url = req.file ? `/uploads/${req.file.filename}` : null;
        const [result] = await db.query(
            `INSERT INTO projects (title_en, description_en, location_en, start_date, end_date, status, budget, progress, contractor, funding_source, is_published, cover_image_url)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [title_en, description_en, location_en, start_date, end_date || null, status || 'Planning', budget || null, progress || 0, contractor, funding_source, isPublished, cover_image_url]
        );
        res.status(201).json({ id: result.insertId, message: 'Project created' });
    } catch (error) {
        console.error('Error creating project:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

// PUT /api/projects/:id (Admin)
exports.updateProject = async (req, res) => {
    try {
        const { title_en, description_en, location_en, start_date, end_date, status, budget, progress, contractor, funding_source, is_published } = req.body;

        // Convert string boolean to integer for MySQL
        const isPublished = is_published === 'true' || is_published === true || is_published === '1' ? 1 : 0;

        let cover_image_url = undefined;
        if (req.file) {
            cover_image_url = `/uploads/${req.file.filename}`;
            // Optional: delete old image if needed
        }
        const setClauses = ['title_en=?', 'description_en=?', 'location_en=?', 'start_date=?', 'end_date=?', 'status=?', 'budget=?', 'progress=?', 'contractor=?', 'funding_source=?', 'is_published=?'];
        const values = [title_en, description_en, location_en, start_date, end_date || null, status, budget || null, progress || 0, contractor, funding_source, isPublished];
        if (cover_image_url !== undefined) {
            setClauses.push('cover_image_url=?');
            values.push(cover_image_url);
        }
        values.push(req.params.id);
        await db.query(`UPDATE projects SET ${setClauses.join(', ')} WHERE id=?`, values);
        res.json({ message: 'Project updated' });
    } catch (error) {
        console.error('Error updating project:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

// DELETE /api/projects/:id (Admin)
exports.deleteProject = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT cover_image_url FROM projects WHERE id = ?', [req.params.id]);
        if (rows.length && rows[0].cover_image_url) {
            const filepath = path.join(__dirname, '../../uploads', path.basename(rows[0].cover_image_url));
            if (fs.existsSync(filepath)) fs.unlinkSync(filepath);
        }
        await db.query('DELETE FROM projects WHERE id = ?', [req.params.id]);
        res.json({ message: 'Project deleted' });
    } catch (error) {
        console.error('Error deleting project:', error);
        res.status(500).json({ message: 'Server error' });
    }
};
