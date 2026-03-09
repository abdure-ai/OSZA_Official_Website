const express = require('express');
const router = express.Router();
const galleryController = require('../controllers/galleryController');
const { authenticateToken } = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Wrap multer so errors come back as JSON, not an HTML crash page
function handleUpload(req, res, next) {
    upload.single('image')(req, res, (err) => {
        if (err) return res.status(400).json({ message: err.message || 'Upload error.' });
        next();
    });
}

// Public
router.get('/', galleryController.getGallery);
router.get('/categories', galleryController.getCategories);
router.get('/all', authenticateToken, galleryController.getAllGallery);

// Admin (protected)
router.post('/', authenticateToken, handleUpload, galleryController.createItem);
router.put('/:id', authenticateToken, handleUpload, galleryController.updateItem);
router.delete('/:id', authenticateToken, galleryController.deleteItem);

module.exports = router;
