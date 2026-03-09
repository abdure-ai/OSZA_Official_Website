const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Get all documents (with optional filtering)
exports.getAllDocuments = async (req, res) => {
    try {
        const { category, search } = req.query;
        let query = 'SELECT * FROM documents WHERE 1=1';
        const params = [];

        if (category && category !== 'All Documents') {
            query += ' AND category = ?';
            params.push(category);
        }

        if (search) {
            query += ' AND (title_en LIKE ? OR title_am LIKE ? OR title_or LIKE ?)';
            const searchTerm = `%${search}%`;
            params.push(searchTerm, searchTerm, searchTerm);
        }

        query += ' ORDER BY created_at DESC';

        const [rows] = await db.query(query, params);
        res.json(rows);
    } catch (error) {
        console.error('Error fetching documents:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Upload Document / Book (Admin)
exports.uploadDocument = async (req, res) => {
    try {
        const docFile = req.files?.file?.[0];
        const coverFile = req.files?.cover?.[0];

        if (!docFile) {
            return res.status(400).json({ message: 'No document file uploaded' });
        }

        const { title_en, category, author, description_en, pages, language, uploaded_by } = req.body;

        const file_url = `/uploads/${docFile.filename}`;
        const file_type = path.extname(docFile.originalname).substring(1);
        const cover_image_url = coverFile ? `/uploads/${coverFile.filename}` : null;

        const [result] = await db.query(
            `INSERT INTO documents 
             (title_en, file_url, file_type, category, cover_image_url, author, description_en, pages, language, uploaded_by) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                title_en,
                file_url,
                file_type,
                category,
                cover_image_url,
                author || null,
                description_en || null,
                pages ? parseInt(pages) : null,
                language || 'English',
                uploaded_by || null
            ]
        );

        res.status(201).json({
            message: 'Resource uploaded successfully',
            document: {
                id: result.insertId,
                title_en,
                file_url,
                file_type,
                category,
                cover_image_url,
                author,
                created_at: new Date()
            }
        });
    } catch (error) {
        console.error('Error uploading document:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};

// Delete Document (Admin)
exports.deleteDocument = async (req, res) => {
    try {
        const { id } = req.params;

        // Get file paths first
        const [rows] = await db.query('SELECT file_url, cover_image_url FROM documents WHERE id = ?', [id]);
        if (rows.length === 0) {
            return res.status(404).json({ message: 'Document not found' });
        }

        const filePath = path.join(__dirname, '../../', rows[0].file_url);
        const coverPath = rows[0].cover_image_url ? path.join(__dirname, '../../', rows[0].cover_image_url) : null;

        // Delete from DB
        await db.query('DELETE FROM documents WHERE id = ?', [id]);

        // Delete files from filesystem
        if (fs.existsSync(filePath)) {
            fs.unlinkSync(filePath);
        }
        if (coverPath && fs.existsSync(coverPath)) {
            fs.unlinkSync(coverPath);
        }

        res.json({ message: 'Document deleted successfully' });
    } catch (error) {
        console.error('Error deleting document:', error);
        res.status(500).json({ message: 'Server Error' });
    }
};
