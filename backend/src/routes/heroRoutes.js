const express = require('express');
const router = express.Router();
const heroController = require('../controllers/heroController');
const { authenticateToken } = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Multer error wrapper — ensures upload errors return JSON, not HTML
function handleUpload(req, res, next) {
    upload.single('media')(req, res, (err) => {
        if (err) {
            return res.status(400).json({ message: err.message || 'File upload error.' });
        }
        next();
    });
}

// Public
router.get('/', heroController.getSlides);

// Admin (protected)
router.get('/all', authenticateToken, heroController.getAllSlides);
router.post('/', authenticateToken, handleUpload, heroController.createSlide);
router.put('/:id', authenticateToken, heroController.updateSlide);
router.delete('/:id', authenticateToken, heroController.deleteSlide);

module.exports = router;
