
-- Създаване на базата данни
CREATE DATABASE IF NOT EXISTS photo_site CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE photo_site;

-- Таблица за галериите
CREATE TABLE IF NOT EXISTS galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    year YEAR,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Таблица за снимките
CREATE TABLE IF NOT EXISTS photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
);
