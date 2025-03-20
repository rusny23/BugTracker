CREATE DATABASE bug_tracker;

USE bug_tracker;

CREATE TABLE bugs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL
    description TEXT NOT NULL,
    steps TEXT,
    severity ENUM('Low', 'Medium', 'High', 'Critical') NOT NULL,
    email VARCHAR(255) NOT NULL,
    attachment VARCHAR(255),
    status ENUM('Open', 'In Progress', 'Resolved') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS accounts (
	id INT AUTO_INCREMENT PRIMARY KEY,
  	username varchar(50) NOT NULL,
  	password varchar(255) NOT NULL,
  	email varchar(100) NOT NULL,
	registered datetime NOT NULL,
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO accounts (id, username, password, email, registered) VALUES (1, 'test', '123', 'test@example.com', '2025-01-01 00:00:00');

