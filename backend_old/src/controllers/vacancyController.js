const db = require('../config/db');

// Get all vacancies
exports.getAllVacancies = async (req, res) => {
    try {
        const { department, type, active } = req.query;
        let query = 'SELECT * FROM vacancies WHERE 1=1';
        const params = [];

        if (department) {
            query += ' AND department = ?';
            params.push(department);
        }

        if (type) {
            query += ' AND vacancy_type = ?';
            params.push(type);
        }

        // Default to active only for public, but allow admin to see all
        if (active === 'false') {
            // Admin might want to see inactive ones
        } else if (active === 'all') {
            // See everything
        } else {
            query += ' AND is_active = 1';
        }

        query += ' ORDER BY created_at DESC';

        const [rows] = await db.query(query, params);
        res.json(rows);
    } catch (error) {
        console.error('Error fetching vacancies:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Get vacancy by ID
exports.getVacancyById = async (req, res) => {
    try {
        const { id } = req.params;
        const [rows] = await db.query('SELECT * FROM vacancies WHERE id = ?', [id]);

        if (rows.length === 0) {
            return res.status(404).json({ message: 'Vacancy not found' });
        }

        res.json(rows[0]);
    } catch (error) {
        console.error('Error fetching vacancy:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Create vacancy (Admin)
exports.createVacancy = async (req, res) => {
    try {
        const {
            title_en, title_am, title_or,
            description_en, description_am, description_or,
            requirements_en, requirements_am, requirements_or,
            department, vacancy_type, location_en, deadline
        } = req.body;

        const [result] = await db.query(
            `INSERT INTO vacancies (
                title_en, title_am, title_or, 
                description_en, description_am, description_or,
                requirements_en, requirements_am, requirements_or,
                department, vacancy_type, location_en, deadline
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                title_en, title_am, title_or,
                description_en, description_am, description_or,
                requirements_en, requirements_am, requirements_or,
                department, vacancy_type, location_en, deadline
            ]
        );

        res.status(201).json({
            message: 'Vacancy created successfully',
            vacancyId: result.insertId
        });
    } catch (error) {
        console.error('Error creating vacancy:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Update vacancy (Admin)
exports.updateVacancy = async (req, res) => {
    try {
        const { id } = req.params;
        const {
            title_en, title_am, title_or,
            description_en, description_am, description_or,
            requirements_en, requirements_am, requirements_or,
            department, vacancy_type, location_en, deadline, is_active
        } = req.body;

        await db.query(
            `UPDATE vacancies SET 
                title_en = ?, title_am = ?, title_or = ?,
                description_en = ?, description_am = ?, description_or = ?,
                requirements_en = ?, requirements_am = ?, requirements_or = ?,
                department = ?, vacancy_type = ?, location_en = ?, 
                deadline = ?, is_active = ?
            WHERE id = ?`,
            [
                title_en, title_am, title_or,
                description_en, description_am, description_or,
                requirements_en, requirements_am, requirements_or,
                department, vacancy_type, location_en, deadline, is_active, id
            ]
        );

        res.json({ message: 'Vacancy updated successfully' });
    } catch (error) {
        console.error('Error updating vacancy:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Delete vacancy (Admin)
exports.deleteVacancy = async (req, res) => {
    try {
        const { id } = req.params;
        await db.query('DELETE FROM vacancies WHERE id = ?', [id]);
        res.json({ message: 'Vacancy deleted successfully' });
    } catch (error) {
        console.error('Error deleting vacancy:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};
