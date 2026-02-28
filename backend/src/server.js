try {
    require('dotenv').config();
} catch (e) {
    console.log('dotenv not found, skipping (using system env)');
}
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const db = require('./config/db');

const app = express();
const PORT = process.env.PORT || 5000;

// DIAGNOSTIC ROUTES (MUST BE AT THE TOP)
app.get('/', (req, res) => {
    const dbConfig = {
        host: process.env.DB_HOST,
        user: process.env.DB_USER,
        database: process.env.DB_NAME,
        has_password: !!process.env.DB_PASSWORD
    };
    res.send(`OSZA Backend is Running - Diagnostic v1.4 (${new Date().toISOString()}) - DB Config: ${JSON.stringify(dbConfig)}`);
});

app.get('/api/health', async (req, res) => {
    try {
        const [rows] = await db.query('SELECT 1 as connected');
        res.json({
            status: 'ok',
            database: 'connected',
            db_name: process.env.DB_NAME,
            env: process.env.NODE_ENV,
            version: 'v1.2'
        });
    } catch (error) {
        res.status(500).json({
            status: 'error',
            database: 'failed',
            message: error.message,
            db_config: {
                host: process.env.DB_HOST,
                user: process.env.DB_USER,
                database: process.env.DB_NAME
            }
        });
    }
});

// Middleware
app.use(helmet({
    crossOriginResourcePolicy: false,
    crossOriginEmbedderPolicy: false,
}));
app.use(cors({
    origin: '*', // Allows all origins, including Vercel
    methods: ['GET', 'POST', 'PUT', 'DELETE'],
    allowedHeaders: ['Content-Type', 'Authorization']
}));
app.use(morgan('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Database Connection Check
db.getConnection()
    .then(connection => {
        console.log('Database connected successfully');
        connection.release();
    })
    .catch(err => {
        console.error('Database connection failed:', err);
    });

const authRoutes = require('./routes/authRoutes');
const userRoutes = require('./routes/userRoutes');
const newsRoutes = require('./routes/newsRoutes');
const documentRoutes = require('./routes/documentRoutes');
const alertRoutes = require('./routes/alertRoutes');
const heroRoutes = require('./routes/heroRoutes');
const directoryRoutes = require('./routes/directoryRoutes'); // Added
const investmentRoutes = require('./routes/investmentRoutes'); // Added
const woredaRoutes = require('./routes/woredaRoutes');
const galleryRoutes = require('./routes/galleryRoutes');
const vacancyRoutes = require('./routes/vacancyRoutes');
const tenderRoutes = require('./routes/tenderRoutes');
const projectRoutes = require('./routes/projectRoutes');
const adminMessageRoutes = require('./routes/adminMessageRoutes');
const statsController = require('./controllers/statsController');

app.use('/api/auth', authRoutes);
app.use('/api/users', userRoutes);
app.use('/api/news', newsRoutes);
app.use('/api/documents', documentRoutes);
app.use('/api/alerts', alertRoutes);
app.use('/api/hero', heroRoutes);
app.use('/api/directory', require('./routes/directoryRoutes'));
app.use('/api/investments', require('./routes/investmentRoutes'));
app.use('/api/woredas', woredaRoutes);
app.use('/api/gallery', galleryRoutes);
app.use('/api/vacancies', vacancyRoutes);
app.use('/api/tenders', tenderRoutes);
app.use('/api/projects', projectRoutes);
const contactRoutes = require('./routes/contactRoutes');
const settingsRoutes = require('./routes/settingsRoutes');
app.use('/api/contact', contactRoutes);
app.use('/api/settings', settingsRoutes);
app.use('/api/admin-message', adminMessageRoutes);

app.get('/api/stats', statsController.getStats);

// Serve static files (uploads)
// NOTE: Must set Cross-Origin-Resource-Policy: cross-origin because helmet
// sets same-origin by default, which blocks <img>/<video> in the Next.js
// frontend (localhost:3000) from loading files served here (localhost:5000).
const path = require('path');
app.use('/uploads', (req, res, next) => {
    res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
    next();
}, express.static(path.join(__dirname, '../uploads')));

// Start Server
app.listen(process.env.PORT || PORT, () => {
    console.log(`Server running on port ${process.env.PORT || PORT}`);
});

module.exports = app;
