const express = require('express');
const router = express.Router();
const directoryController = require('../controllers/directoryController');
const { authenticateToken } = require('../middleware/authMiddleware');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Storage config
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        const dir = 'uploads/directory/';
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
        cb(null, dir);
    },
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname));
    }
});

const upload = multer({ storage });

// Public routes
router.get('/', directoryController.getAllContacts);

// Admin routes
router.post('/', authenticateToken, upload.single('photo'), directoryController.createContact);
router.put('/:id', authenticateToken, upload.single('photo'), directoryController.updateContact);
router.delete('/:id', authenticateToken, directoryController.deleteContact);

module.exports = router;
