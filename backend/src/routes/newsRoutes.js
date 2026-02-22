const express = require('express');
const router = express.Router();
const newsController = require('../controllers/newsController');
const { authenticateToken } = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Multer error wrapper
function handleUpload(req, res, next) {
    upload.single('thumbnail')(req, res, (err) => {
        if (err) return res.status(400).json({ message: err.message || 'File upload error.' });
        next();
    });
}

// Public Routes
router.get('/', newsController.getAllNews);
router.get('/:id', newsController.getNewsById);

// Protected Routes (Admin only)
router.post('/', authenticateToken, handleUpload, newsController.createNews);
router.put('/:id', authenticateToken, handleUpload, newsController.updateNews);
router.delete('/:id', authenticateToken, newsController.deleteNews);

module.exports = router;
