const db = require('../config/db');

// GET active woredas (public)
exports.getWoredas = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT * FROM woredas WHERE is_active = 1 ORDER BY name_en ASC'
        );
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET single woreda by slug (public)
exports.getWoredaBySlug = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT * FROM woredas WHERE slug = ? AND is_active = 1',
            [req.params.slug]
        );
        if (rows.length === 0) return res.status(404).json({ message: 'Woreda not found' });
        res.json(rows[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// GET all woredas (admin — includes inactive)
exports.getAllWoredas = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM woredas ORDER BY name_en ASC');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// POST create woreda (admin)
exports.createWoreda = async (req, res) => {
    try {
        const {
            name_en, name_am, name_or,
            slug,
            description_en, description_am, description_or,
            population, area_km2, established_year,
            capital_en, capital_am, capital_or,
            administrator_name, administrator_title,
            contact_phone, contact_email,
            address_en, address_am, address_or,
            is_active,
        } = req.body;

        if (!name_en || !slug) {
            return res.status(400).json({ message: 'name_en and slug are required.' });
        }

        const banner_url = req.files?.banner?.[0]
            ? `/uploads/${req.files.banner[0].filename}` : null;
        const logo_url = req.files?.logo?.[0]
            ? `/uploads/${req.files.logo[0].filename}` : null;
        const administrator_photo_url = req.files?.admin_photo?.[0]
            ? `/uploads/${req.files.admin_photo[0].filename}` : null;

        const isActive = is_active === 'true' || is_active === true || is_active === '1' ? 1 : 0;

        const [result] = await db.query(
            `INSERT INTO woredas
             (name_en, name_am, name_or, slug, 
              description_en, description_am, description_or, 
              population, area_km2, established_year,
              capital_en, capital_am, capital_or, 
              administrator_name, administrator_title,
              contact_phone, contact_email, 
              address_en, address_am, address_or, 
              banner_url, logo_url, administrator_photo_url, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                name_en, name_am || null, name_or || null,
                slug.toLowerCase().replace(/\s+/g, '-'),
                description_en || null, description_am || null, description_or || null,
                population || null, area_km2 || null,
                established_year || null,
                capital_en || null, capital_am || null, capital_or || null,
                administrator_name || null,
                administrator_title || 'Woreda Administrator',
                contact_phone || null, contact_email || null,
                address_en || null, address_am || null, address_or || null,
                banner_url, logo_url, administrator_photo_url,
                isActive,
            ]
        );

        res.status(201).json({ message: 'Woreda created', id: result.insertId });
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(409).json({ message: 'A woreda with that slug already exists.' });
        }
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// PUT update woreda (admin)
exports.updateWoreda = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            name_en, name_am, name_or,
            slug,
            description_en, description_am, description_or,
            population, area_km2, established_year,
            capital_en, capital_am, capital_or,
            administrator_name, administrator_title,
            contact_phone, contact_email,
            address_en, address_am, address_or,
            is_active,
        } = req.body;

        // Only update image paths if new files were uploaded
        const banner_url = req.files?.banner?.[0]
            ? `/uploads/${req.files.banner[0].filename}` : undefined;
        const logo_url = req.files?.logo?.[0]
            ? `/uploads/${req.files.logo[0].filename}` : undefined;
        const administrator_photo_url = req.files?.admin_photo?.[0]
            ? `/uploads/${req.files.admin_photo[0].filename}` : undefined;

        // Build dynamic SET clause
        const fields = {
            name_en, name_am, name_or,
            slug,
            description_en, description_am, description_or,
            population, area_km2, established_year,
            capital_en, capital_am, capital_or,
            administrator_name, administrator_title,
            contact_phone, contact_email,
            address_en, address_am, address_or,
            is_active: is_active !== undefined ? (is_active === 'true' || is_active === true || is_active === '1' ? 1 : 0) : undefined,
        };
        if (banner_url !== undefined) fields.banner_url = banner_url;
        if (logo_url !== undefined) fields.logo_url = logo_url;
        if (administrator_photo_url !== undefined) fields.administrator_photo_url = administrator_photo_url;

        const entries = Object.entries(fields).filter(([, v]) => v !== undefined);
        if (entries.length === 0) return res.json({ message: 'Nothing to update' });

        const sql = `UPDATE woredas SET ${entries.map(([k]) => `${k}=?`).join(', ')} WHERE id=?`;
        await db.query(sql, [...entries.map(([, v]) => v), id]);
        res.json({ message: 'Woreda updated' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// DELETE woreda (admin)
exports.deleteWoreda = async (req, res) => {
    try {
        await db.query('DELETE FROM woredas WHERE id = ?', [req.params.id]);
        res.json({ message: 'Woreda deleted' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};
