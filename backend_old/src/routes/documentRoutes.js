const express = require('express');
const router = express.Router();
const documentController = require('../controllers/documentController');
const upload = require('../middleware/uploadMiddleware');
const { authenticateToken } = require('../middleware/authMiddleware');

// Public Routes
router.get('/', documentController.getAllDocuments);

// Protected Routes (Admin only)
router.post('/upload', authenticateToken, upload.fields([
    { name: 'file', maxCount: 1 },
    { name: 'cover', maxCount: 1 }
]), documentController.uploadDocument);
router.delete('/:id', authenticateToken, documentController.deleteDocument);

module.exports = router;
