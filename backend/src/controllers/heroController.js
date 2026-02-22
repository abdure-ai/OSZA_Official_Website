const db = require('../config/db');

// GET all hero slides (public)
exports.getSlides = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET all slides for admin (all, including inactive)
exports.getAllSlides = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM hero_slides ORDER BY sort_order ASC');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// POST create slide (admin)
exports.createSlide = async (req, res) => {
    try {
        const {
            title_en, title_am, title_or,
            subtitle_en, subtitle_am, subtitle_or,
            cta_text, cta_text_am, cta_text_or,
            cta_url, media_type, sort_order, is_active
        } = req.body;

        if (!req.file && !req.body.media_url) {
            return res.status(400).json({ message: 'A media file or URL is required.' });
        }

        const media_url = req.file ? `/uploads/${req.file.filename}` : req.body.media_url;
        const type = media_type || (req.file?.mimetype.startsWith('video') ? 'video' : 'image');

        const [result] = await db.query(
            `INSERT INTO hero_slides (
                title_en, title_am, title_or, 
                subtitle_en, subtitle_am, subtitle_or, 
                media_url, media_type, 
                cta_text, cta_text_am, cta_text_or, 
                cta_url, sort_order, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                title_en || null, title_am || null, title_or || null,
                subtitle_en || null, subtitle_am || null, subtitle_or || null,
                media_url, type,
                cta_text || null, cta_text_am || null, cta_text_or || null,
                cta_url || null, sort_order || 0, is_active ? 1 : 1
            ]
        );

        res.status(201).json({ message: 'Slide created', id: result.insertId, media_url });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// PUT update slide (admin)
exports.updateSlide = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            title_en, title_am, title_or,
            subtitle_en, subtitle_am, subtitle_or,
            cta_text, cta_text_am, cta_text_or,
            cta_url, sort_order, is_active
        } = req.body;

        await db.query(
            `UPDATE hero_slides SET 
                title_en=?, title_am=?, title_or=?, 
                subtitle_en=?, subtitle_am=?, subtitle_or=?, 
                cta_text=?, cta_text_am=?, cta_text_or=?, 
                cta_url=?, sort_order=?, is_active=? 
             WHERE id=?`,
            [
                title_en || null, title_am || null, title_or || null,
                subtitle_en || null, subtitle_am || null, subtitle_or || null,
                cta_text || null, cta_text_am || null, cta_text_or || null,
                cta_url || null, sort_order || 0, is_active ? 1 : 0, id
            ]
        );
        res.json({ message: 'Slide updated' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// DELETE slide (admin)
exports.deleteSlide = async (req, res) => {
    try {
        const { id } = req.params;
        await db.query('DELETE FROM hero_slides WHERE id = ?', [id]);
        res.json({ message: 'Slide deleted' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};
