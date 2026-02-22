const db = require('../config/db');

exports.getSettings = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM office_settings WHERE id = 1');
        if (rows.length === 0) return res.status(404).json({ message: 'Settings not found' });
        res.json(rows[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};

exports.updateSettings = async (req, res) => {
    try {
        const {
            phone, email, address, working_hours, map_url,
            facebook_url, twitter_url, linkedin_url, youtube_url
        } = req.body;

        const sql = `
            UPDATE office_settings 
            SET phone=?, email=?, address=?, working_hours=?, map_url=?, 
                facebook_url=?, twitter_url=?, linkedin_url=?, youtube_url=?
            WHERE id = 1
        `;

        await db.query(sql, [
            phone, email, address, working_hours, map_url,
            facebook_url, twitter_url, linkedin_url, youtube_url
        ]);

        res.json({ message: 'Settings updated successfully' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server Error' });
    }
};
