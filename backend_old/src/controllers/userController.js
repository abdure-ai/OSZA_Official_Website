const db = require('../config/db');
const bcrypt = require('bcryptjs');

exports.getAllUsers = async (req, res) => {
    try {
        const [users] = await db.execute('SELECT id, username, email, role, created_at FROM users');
        res.json(users);
    } catch (err) {
        console.error(err);
        res.status(500).json({ message: 'Server error retrieving users' });
    }
};

exports.createUser = async (req, res) => {
    const { username, email, password, role } = req.body;

    if (!username || !email || !password || !role) {
        return res.status(400).json({ message: 'All fields are required' });
    }

    try {
        // Check if user exists
        const [existing] = await db.execute('SELECT id FROM users WHERE email = ?', [email]);
        if (existing.length > 0) {
            return res.status(400).json({ message: 'User already exists' });
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        await db.execute(
            'INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)',
            [username, email, hashedPassword, role]
        );

        res.status(201).json({ message: 'User created successfully' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ message: 'Error creating user' });
    }
};

exports.deleteUser = async (req, res) => {
    const userId = req.params.id;

    try {
        await db.execute('DELETE FROM users WHERE id = ?', [userId]);
        res.json({ message: 'User deleted successfully' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ message: 'Error deleting user' });
    }
};
