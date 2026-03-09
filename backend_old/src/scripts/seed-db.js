const mysql = require('mysql2/promise');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../../.env') });

const newsData = [
    {
        title_en: "New Irrigation Project Launched in Dawa Chefa",
        content_en: "The project aims to benefit over 500 farmer households in the district. It will provide sustainable water access for year-round farming, significantly boosting local agricultural productivity.",
        category: "news",
        status: "published",
        published_at: new Date('2025-10-24')
    },
    {
        title_en: "Zone Administration Holds Annual Budget Review",
        content_en: "Officials discussed fiscal allocation for the upcoming year with a focus on education and healthcare improvements across the zone.",
        category: "news",
        status: "published",
        published_at: new Date('2025-10-20')
    },
    {
        title_en: "Health Bureau Announces Vaccination Campaign",
        content_en: "A region-wide vaccination drive against measles will commence next week. Parents are urged to bring their children to the nearest health center.",
        category: "news",
        status: "published",
        published_at: new Date('2025-10-18')
    }
];

async function seedDb() {
    try {
        const connection = await mysql.createConnection({
            host: process.env.DB_HOST,
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD,
            database: process.env.DB_NAME
        });

        console.log('Connected to MySQL server.');

        for (const news of newsData) {
            await connection.execute(
                'INSERT INTO posts (title_en, content_en, category, status, published_at) VALUES (?, ?, ?, ?, ?)',
                [news.title_en, news.content_en, news.category, news.status, news.published_at]
            );
        }

        console.log('Database seeded successfully.');
        await connection.end();
    } catch (err) {
        console.error('Error seeding database:', err);
        process.exit(1);
    }
}

seedDb();
