const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { authenticateToken, authorizeRole } = require('../middleware/authMiddleware');

router.post('/login', authController.login);
router.post('/register', authenticateToken, authorizeRole(['super_admin']), authController.register);

module.exports = router;
