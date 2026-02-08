-- CREATE DATABASE todo_db;
-- USE todo_db;
-- CREATE TABLE users(
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     first_name VARCHAR(20) NOT NULL,
--     last_name VARCHAR(20) NOT NULL,
--     username VARCHAR(20) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     profile_url VARCHAR(255) NOT NULL,
--     created_at DATETIME DEFAULT NOW(),
--     CONSTRAINT check_first_name_length CHECK (CHAR_LENGTH(first_name) >= 3),
--     CONSTRAINT check_last_name_length CHECK (CHAR_LENGTH(last_name) >= 3),
--     CONSTRAINT check_username_length CHECK (CHAR_LENGTH(username) >= 3),
--     CONSTRAINT check_password_length CHECK (CHAR_LENGTH(password) >= 50),
--     CONSTRAINT check_profile_url_length CHECK (CHAR_LENGTH(profile_url) >= 8)
-- );
-- ALTER TABLE users
-- AUTO_INCREMENT = 1000;
-- CREATE TABLE todos(
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     title VARCHAR(100) NOT NULL,
--     description VARCHAR(255) NOT NULL,
--     is_done BOOLEAN DEFAULT FALSE,
--     image_url VARCHAR(300) DEFAULT NULL,
--     created_at DATETIME DEFAULT NOW(),
--     updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),
--     user_id INT NOT NULL,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
--     CONSTRAINT check_title_length CHECK (CHAR_LENGTH(title) >= 2),
--     CONSTRAINT check_description_length CHECK (CHAR_LENGTH(description) >= 2),
--     CONSTRAINT check_image_url_length CHECK (image_url IS NULL OR CHAR_LENGTH(image_url) >= 8)
-- );
-- ALTER TABLE todos
-- AUTO_INCREMENT = 1000;
CREATE DATABASE todo_db;
USE todo_db;
CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_url VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_first_name_length CHECK (CHAR_LENGTH(first_name) >= 3),
    CONSTRAINT check_last_name_length CHECK (CHAR_LENGTH(last_name) >= 3),
    CONSTRAINT check_username_length CHECK (CHAR_LENGTH(username) >= 3),
    CONSTRAINT check_password_length CHECK (CHAR_LENGTH(password) >= 50),
    CONSTRAINT check_profile_url_length CHECK (CHAR_LENGTH(profile_url) >= 8)
);
ALTER TABLE users
AUTO_INCREMENT = 1000;
CREATE TABLE todos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    image_url VARCHAR(300) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT check_title_length CHECK (CHAR_LENGTH(title) >= 2),
    CONSTRAINT check_description_length CHECK (CHAR_LENGTH(description) >= 2),
    CONSTRAINT check_image_url_length CHECK (image_url IS NULL OR CHAR_LENGTH(image_url) >= 8)
);
ALTER TABLE todos
AUTO_INCREMENT = 1000;
