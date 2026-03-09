const express = require('express');
const router = express.Router();
const vacancyController = require('../controllers/vacancyController');
const { authenticateToken } = require('../middleware/authMiddleware');

// Public routes
router.get('/', vacancyController.getAllVacancies);
router.get('/:id', vacancyController.getVacancyById);

// Protected Admin routes
router.post('/', authenticateToken, vacancyController.createVacancy);
router.put('/:id', authenticateToken, vacancyController.updateVacancy);
router.delete('/:id', authenticateToken, vacancyController.deleteVacancy);

module.exports = router;
