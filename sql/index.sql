CREATE DATABASE todo_db;
USE todo_db;
CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_url VARCHAR(300) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    CONSTRAINT check_username_length CHECK (CHAR_LENGTH(username) >= 3)
);
CREATE TABLE todos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(250) NOT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    image_url VARCHAR(300) DEFAULT NULL,
    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW(),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);