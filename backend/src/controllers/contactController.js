const db = require('../config/db');

exports.submitMessage = async (req, res) => {
    const { name, email, phone, subject, message } = req.body;

    if (!name || !email || !message) {
        return res.status(400).json({ message: 'Name, email, and message are required' });
    }

    try {
        await db.query(
            'INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)',
            [name, email, phone || null, subject || null, message]
        );
        res.status(201).json({ message: 'Message sent successfully' });
    } catch (error) {
        console.error('Error saving contact message:', error);
        res.status(500).json({ message: 'Internal server error' });
    }
};

exports.getMessages = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM contact_messages ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        console.error('Error fetching contact messages:', error);
        res.status(500).json({ message: 'Internal server error' });
    }
};
