const express = require('express');
const router = express.Router();
const woredaController = require('../controllers/woredaController');
const { authenticateToken } = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Multer: accept banner + logo + admin_photo as separate fields
const woredaUpload = upload.fields([
    { name: 'banner', maxCount: 1 },
    { name: 'logo', maxCount: 1 },
    { name: 'admin_photo', maxCount: 1 },
]);

function handleUpload(req, res, next) {
    woredaUpload(req, res, (err) => {
        if (err) return res.status(400).json({ message: err.message || 'Upload error.' });
        next();
    });
}

// Public
router.get('/', woredaController.getWoredas);
router.get('/all', authenticateToken, woredaController.getAllWoredas);
router.get('/:slug', woredaController.getWoredaBySlug);

// Admin (protected)
router.post('/', authenticateToken, handleUpload, woredaController.createWoreda);
router.put('/:id', authenticateToken, handleUpload, woredaController.updateWoreda);
router.delete('/:id', authenticateToken, woredaController.deleteWoreda);

module.exports = router;
