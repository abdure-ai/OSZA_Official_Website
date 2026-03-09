/**
 * migrate-woredas.js
 * Creates the `woredas` table if it doesn't exist and seeds sample woreda data.
 * Run once: node src/scripts/migrate-woredas.js
 */

const mysql = require('mysql2/promise');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../../.env') });

const SAMPLE_WOREDAS = [
    {
        name_en: 'Dawa Chefa',
        slug: 'dawa-chefa',
        description_en: 'Dawa Chefa woreda is one of the most productive agricultural areas in the Oromo Special Zone, known for its fertile lands along the Goletti River and its diverse farming communities.',
        population: '145,000',
        area_km2: '1,240',
        established_year: '1995',
        capital_en: 'Dawa Chefa Town',
        administrator_name: 'Ato Gemechu Tadesse',
        administrator_title: 'Woreda Administrator',
        contact_phone: '+251 33 556 0011',
        contact_email: 'admin@dawachefa.oromozone.gov.et',
        address_en: 'Main Government Building, Dawa Chefa Town',
    },
    {
        name_en: 'Bate',
        slug: 'bate',
        description_en: 'Bate woreda serves as a key commercial hub in the Oromo Special Zone. Its central location makes it vital for trade and regional connectivity, with a thriving market and growing urban population.',
        population: '98,000',
        area_km2: '820',
        established_year: '1995',
        capital_en: 'Bate Town',
        administrator_name: 'Ato Abdulkadir Hassen',
        administrator_title: 'Woreda Administrator',
        contact_phone: '+251 33 556 0022',
        contact_email: 'admin@bate.oromozone.gov.et',
        address_en: 'Woreda Administration Office, Bate',
    },
    {
        name_en: 'Kemise',
        slug: 'kemise',
        description_en: 'Kemise is the administrative capital of the Oromo Special Zone. It is the main centre for government services, education, healthcare, and economic activity across the entire zone.',
        population: '210,000',
        area_km2: '680',
        established_year: '1991',
        capital_en: 'Kemise City',
        administrator_name: 'Ato Tilahun Bekele',
        administrator_title: 'City Administrator',
        contact_phone: '+251 33 556 0033',
        contact_email: 'admin@kemise.oromozone.gov.et',
        address_en: 'City Hall, Main Road, Kemise',
    },
    {
        name_en: 'Hakim',
        slug: 'hakim',
        description_en: 'Hakim woreda is a growing district known for its livestock production and pastoral communities. It borders the Afar Region and plays an important role in cross-regional trade.',
        population: '76,000',
        area_km2: '1,580',
        established_year: '1997',
        capital_en: 'Hakim Town',
        administrator_name: 'Ato Murad Jemal',
        administrator_title: 'Woreda Administrator',
        contact_phone: '+251 33 556 0044',
        contact_email: 'admin@hakim.oromozone.gov.et',
        address_en: 'Woreda Government Office, Hakim',
    },
    {
        name_en: 'Bati',
        slug: 'bati',
        description_en: 'Bati woreda is famed for its weekly market, one of the largest open-air markets in the Horn of Africa. It is a cultural and commercial melting pot drawing traders from across Ethiopia.',
        population: '130,000',
        area_km2: '950',
        established_year: '1993',
        capital_en: 'Bati Town',
        administrator_name: 'Ato Seid Mohammed',
        administrator_title: 'Woreda Administrator',
        contact_phone: '+251 33 556 0055',
        contact_email: 'admin@bati.oromozone.gov.et',
        address_en: 'Administration Block, Bati Town',
    },
];

async function run() {
    const connection = await mysql.createConnection({
        host: process.env.DB_HOST,
        user: process.env.DB_USER,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_NAME,
    });

    // Create table
    await connection.execute(`
        CREATE TABLE IF NOT EXISTS woredas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name_en VARCHAR(255) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            description_en TEXT,
            population VARCHAR(50),
            area_km2 VARCHAR(50),
            established_year VARCHAR(10),
            capital_en VARCHAR(255),
            administrator_name VARCHAR(255),
            administrator_title VARCHAR(255) DEFAULT 'Woreda Administrator',
            contact_phone VARCHAR(50),
            contact_email VARCHAR(255),
            address_en VARCHAR(255),
            banner_url VARCHAR(1024),
            logo_url VARCHAR(1024),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    `);
    console.log('✅  woredas table ready.');

    // Seed data (skip if slug already exists)
    let inserted = 0;
    for (const w of SAMPLE_WOREDAS) {
        const [existing] = await connection.execute(
            'SELECT id FROM woredas WHERE slug = ?', [w.slug]
        );
        if (existing.length > 0) { console.log(`   skipped: ${w.slug} (already exists)`); continue; }

        await connection.execute(
            `INSERT INTO woredas
             (name_en, slug, description_en, population, area_km2, established_year,
              capital_en, administrator_name, administrator_title,
              contact_phone, contact_email, address_en, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)`,
            [
                w.name_en, w.slug, w.description_en, w.population, w.area_km2,
                w.established_year, w.capital_en, w.administrator_name,
                w.administrator_title, w.contact_phone, w.contact_email, w.address_en,
            ]
        );
        inserted++;
        console.log(`   inserted: ${w.name_en}`);
    }

    console.log(`✅  Seeding complete. ${inserted} new woreda(s) inserted.`);
    await connection.end();
}

run().catch((err) => { console.error(err); process.exit(1); });
