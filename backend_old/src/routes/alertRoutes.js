const express = require('express');
const router = express.Router();
const alertController = require('../controllers/alertController');
const { authenticateToken } = require('../middleware/authMiddleware');

// Public
router.get('/', alertController.getActiveAlerts);

// Admin (protected)
router.get('/all', authenticateToken, alertController.getAllAlerts);
router.post('/', authenticateToken, alertController.createAlert);
router.put('/:id/toggle', authenticateToken, alertController.toggleAlert);
router.delete('/:id', authenticateToken, alertController.deleteAlert);

module.exports = router;
