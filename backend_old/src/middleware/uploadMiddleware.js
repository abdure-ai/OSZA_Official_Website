const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Ensure upload directory exists
const uploadDir = path.join(__dirname, '../../uploads');
if (!fs.existsSync(uploadDir)) {
    fs.mkdirSync(uploadDir, { recursive: true });
}

// Storage configuration
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, uploadDir);
    },
    filename: (req, file, cb) => {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, uniqueSuffix + path.extname(file.originalname));
    }
});

// File filter
const fileFilter = (req, file, cb) => {
    // Accept images, documents, and videos (for hero slides)
    const allowedTypes = /jpeg|jpg|png|gif|pdf|doc|docx|mp4|webm|mov/;
    const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
    const mimetypeOk = /image|video|application\/pdf|application\/msword|application\/vnd/.test(file.mimetype);

    if (extname && mimetypeOk) {
        return cb(null, true);
    } else {
        cb(new Error('File type not supported.'));
    }
};

const upload = multer({
    storage: storage,
    limits: { fileSize: 20 * 1024 * 1024 }, // 20MB limit (for videos)
    fileFilter: fileFilter
});

module.exports = upload;
