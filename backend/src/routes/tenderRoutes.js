const express = require('express');
const router = express.Router();
const tenderController = require('../controllers/tenderController');
const { authenticateToken } = require('../middleware/authMiddleware');
const multer = require('multer');
const path = require('path');

// Configure Multer for PDF uploads
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, 'uploads/');
    },
    filename: (req, file, cb) => {
        cb(null, `tender-${Date.now()}${path.extname(file.originalname)}`);
    }
});

const upload = multer({
    storage: storage,
    fileFilter: (req, file, cb) => {
        const filetypes = /pdf|doc|docx/;
        const extname = filetypes.test(path.extname(file.originalname).toLowerCase());
        if (extname) {
            return cb(null, true);
        } else {
            cb('Error: PDFs and Word Docs only!');
        }
    }
});

// Public routes
router.get('/', tenderController.getAllTenders);

// Protected Admin routes
router.post('/', authenticateToken, upload.single('document'), tenderController.createTender);
router.put('/:id', authenticateToken, upload.single('document'), tenderController.updateTender);
router.delete('/:id', authenticateToken, tenderController.deleteTender);

module.exports = router;
