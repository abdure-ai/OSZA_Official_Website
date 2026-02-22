-- Database Schema for Oromo Special Zone Administration Website

CREATE DATABASE IF NOT EXISTS osza_website;
USE osza_website;

-- Users Table (RBAC)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'content_editor', 'audit') NOT NULL DEFAULT 'content_editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Posts Table (News, Press Releases)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255),
    title_am VARCHAR(255),
    title_or VARCHAR(255),
    content_en TEXT,
    content_am TEXT,
    content_or TEXT,
    category ENUM('news', 'press_release', 'update') NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    thumbnail_url VARCHAR(1024),
    admin_id INT,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Documents Table (Digital Library & Resources)
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255),
    title_am VARCHAR(255),
    title_or VARCHAR(255),
    file_url VARCHAR(1024) NOT NULL,
    file_type VARCHAR(50),
    category VARCHAR(100),
    cover_image_url VARCHAR(1024),
    author VARCHAR(255),
    description_en TEXT,
    pages INT,
    language VARCHAR(50) DEFAULT 'English',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Leadership Table
CREATE TABLE IF NOT EXISTS leadership (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255),
    name_am VARCHAR(255),
    name_or VARCHAR(255),
    position_en VARCHAR(255),
    position_am VARCHAR(255),
    position_or VARCHAR(255),
    bio_en TEXT,
    bio_am TEXT,
    bio_or TEXT,
    photo_url VARCHAR(1024),
    rank_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Emergency Alerts Table
CREATE TABLE IF NOT EXISTS emergency_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_en VARCHAR(255),
    message_am VARCHAR(255),
    message_or VARCHAR(255),
    severity ENUM('info', 'warning', 'critical') DEFAULT 'info',
    is_active BOOLEAN DEFAULT FALSE,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hero Slides Table
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255),
    subtitle_en VARCHAR(255),
    media_url VARCHAR(1024),
    media_type ENUM('image', 'video') DEFAULT 'image',
    cta_text VARCHAR(255),
    cta_url VARCHAR(1024),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Woredas Table
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
);
