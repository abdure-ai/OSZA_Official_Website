const express = require('express');
const router = express.Router();
const settingsController = require('../controllers/settingsController');
const { authenticateToken } = require('../middleware/authMiddleware');

router.get('/', settingsController.getSettings);
router.put('/', authenticateToken, settingsController.updateSettings);

module.exports = router;
