const express = require('express');
const router = express.Router();
const investmentController = require('../controllers/investmentController');
const { authenticateToken } = require('../middleware/authMiddleware');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Storage config
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        const dir = 'uploads/investments/';
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
        cb(null, dir);
    },
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname));
    }
});

const upload = multer({ storage });

// Public routes
router.get('/', investmentController.getAllInvestments);

// Admin routes
router.post('/', authenticateToken, upload.single('thumbnail'), investmentController.createInvestment);
router.put('/:id', authenticateToken, upload.single('thumbnail'), investmentController.updateInvestment);
router.delete('/:id', authenticateToken, investmentController.deleteInvestment);

module.exports = router;
