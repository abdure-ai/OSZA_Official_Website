const express = require('express');
const router = express.Router();
const multer = require('multer');
const path = require('path');
const adminMessageController = require('../controllers/adminMessageController');
const { authenticateToken } = require('../middleware/authMiddleware');

const storage = multer.diskStorage({
    destination: (req, file, cb) => cb(null, 'uploads/'),
    filename: (req, file, cb) => cb(null, `admin-msg-${Date.now()}${path.extname(file.originalname)}`)
});
const upload = multer({
    storage,
    fileFilter: (req, file, cb) => {
        const ext = path.extname(file.originalname).toLowerCase();
        if (['.jpg', '.jpeg', '.png', '.webp'].includes(ext)) cb(null, true);
        else cb(new Error('Only image files allowed'));
    }
});

// Public
router.get('/', adminMessageController.getMessage);

// Admin
router.put('/', authenticateToken, upload.single('photo'), adminMessageController.updateMessage);

module.exports = router;
