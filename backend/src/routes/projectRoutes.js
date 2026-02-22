const express = require('express');
const router = express.Router();
const multer = require('multer');
const path = require('path');
const projectController = require('../controllers/projectController');
const { authenticateToken } = require('../middleware/authMiddleware');

const storage = multer.diskStorage({
    destination: (req, file, cb) => cb(null, 'uploads/'),
    filename: (req, file, cb) => cb(null, `project-${Date.now()}${path.extname(file.originalname)}`)
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
router.get('/', projectController.getAllProjects);
router.get('/:id', projectController.getProjectById);

// Admin
router.get('/admin/all', authenticateToken, projectController.getAllProjectsAdmin);
router.post('/', authenticateToken, upload.single('cover_image'), projectController.createProject);
router.put('/:id', authenticateToken, upload.single('cover_image'), projectController.updateProject);
router.delete('/:id', authenticateToken, projectController.deleteProject);

module.exports = router;
