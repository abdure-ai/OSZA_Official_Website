/**
 * migrate-gallery.js
 * 1) Adds administrator_photo_url column to woredas table
 * 2) Creates gallery_items table
 * 3) Seeds sample gallery items
 * Run: node src/scripts/migrate-gallery.js
 */

const mysql = require('mysql2/promise');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../../.env') });

async function run() {
    const conn = await mysql.createConnection({
        host: process.env.DB_HOST,
        user: process.env.DB_USER,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_NAME,
    });

    // 1) Add administrator_photo_url to woredas if missing
    const [cols] = await conn.execute(`SHOW COLUMNS FROM woredas LIKE 'administrator_photo_url'`);
    if (cols.length === 0) {
        await conn.execute(`ALTER TABLE woredas ADD COLUMN administrator_photo_url VARCHAR(1024) DEFAULT NULL`);
        console.log('✅  Added administrator_photo_url to woredas.');
    } else {
        console.log('   administrator_photo_url already exists — skipped.');
    }

    // 2) Create gallery_items table
    await conn.execute(`
        CREATE TABLE IF NOT EXISTS gallery_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            image_url VARCHAR(1024) NOT NULL,
            category VARCHAR(100) NOT NULL,
            woreda_id INT DEFAULT NULL,
            sort_order INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (woreda_id) REFERENCES woredas(id) ON DELETE SET NULL
        )
    `);
    console.log('✅  gallery_items table ready.');

    // 3) Fetch woreda ids for seeding
    const [woredas] = await conn.execute('SELECT id, slug FROM woredas');
    const bySlug = Object.fromEntries(woredas.map(w => [w.slug, w.id]));

    const SEED = [
        { title: 'Zone Administration Complex', category: 'Infrastructure', woreda_slug: null },
        { title: 'Dawa Chefa Irrigation Project Opening', category: 'Agriculture', woreda_slug: 'dawa-chefa' },
        { title: 'Kemise Annual Health Fair', category: 'Health', woreda_slug: 'kemise' },
        { title: 'Bate Market Day', category: 'Events', woreda_slug: 'bate' },
        { title: 'Hakim Livestock Expo', category: 'Agriculture', woreda_slug: 'hakim' },
        { title: 'Bati Cultural Festival', category: 'Culture', woreda_slug: 'bati' },
        { title: 'Zone Education Summit', category: 'Education', woreda_slug: null },
        { title: 'Road Construction Phase 2', category: 'Infrastructure', woreda_slug: 'dawa-chefa' },
        { title: 'Kemise Health Centre Inauguration', category: 'Health', woreda_slug: 'kemise' },
        { title: 'Bati Weekly Market', category: 'Events', woreda_slug: 'bati' },
    ];

    // Placeholder image URLs (Unsplash random that will definitely load)
    const placeholderImages = [
        '/uploads/placeholder-gallery-1.jpg',
        '/uploads/placeholder-gallery-2.jpg',
        '/uploads/placeholder-gallery-3.jpg',
        '/uploads/placeholder-gallery-4.jpg',
        '/uploads/placeholder-gallery-5.jpg',
    ];

    let inserted = 0;
    for (let i = 0; i < SEED.length; i++) {
        const s = SEED[i];
        const wid = s.woreda_slug ? (bySlug[s.woreda_slug] || null) : null;
        const imgUrl = placeholderImages[i % placeholderImages.length];
        await conn.execute(
            `INSERT INTO gallery_items (title, image_url, category, woreda_id, sort_order) VALUES (?, ?, ?, ?, ?)`,
            [s.title, imgUrl, s.category, wid, i]
        );
        inserted++;
    }
    console.log(`✅  Seeded ${inserted} gallery items.`);
    await conn.end();
}

run().catch(err => { console.error(err); process.exit(1); });
