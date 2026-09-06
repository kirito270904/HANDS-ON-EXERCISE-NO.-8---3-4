-- ===================================================
-- Database: registration_db
-- PHP Output #3 & #4 - Saint Michael College of Caraga
-- ===================================================

CREATE DATABASE IF NOT EXISTS registration_db;
USE registration_db;

CREATE TABLE IF NOT EXISTS persons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
