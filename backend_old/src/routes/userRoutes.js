const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const { authenticateToken, authorizeRole } = require('../middleware/authMiddleware');

// All routes require Super Admin privileges
router.use(authenticateToken, authorizeRole(['super_admin']));

router.get('/', userController.getAllUsers);
router.post('/', userController.createUser);
router.delete('/:id', userController.deleteUser);

module.exports = router;
