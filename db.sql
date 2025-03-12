-- CREATE DATABASE `code_snippets`;

-- USE `code_snippets`;

-- CREATE TABLE `snippets` (
--     id INT AUTO_INCREMENT PRIMARY KEY
--     title VARCHAR(255) NOT NULL,
--     description text,
--     code text NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- );

-- -- Tags table

-- CREATE TABLE `tags`(
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) UNIQUE NOT NULL
-- );

-- -- Snippets tags (uses a many to many relationship)  for the table

-- CREATE TABLE `snippet_tags`(
--     snippet_id INT,
--     tag_id INT,
--     PRIMARY KEY(snippet_id, tag_id),
--     FOREIGN KEY (snippet_id) REFERENCES snippets(id) ON DELETE CASCADE,
--     FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
-- )


CREATE DATABASE code_snippets;
USE code_snippets;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Snippets Table
CREATE TABLE snippets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Foreign key to users
    title VARCHAR(255) NOT NULL,
    description TEXT,
    code TEXT NOT NULL,
    language VARCHAR(50) NOT NULL, -- New column added here
    visibility ENUM('public', 'private') DEFAULT 'private',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Tags Table
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

-- Snippet Tags (Many-to-Many Relationship)
CREATE TABLE snippet_tags (
    snippet_id INT,
    tag_id INT,
    PRIMARY KEY (snippet_id, tag_id),
    FOREIGN KEY (snippet_id) REFERENCES snippets(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);


-- later added some columns to the page
ALTER TABLE users 
ADD COLUMN bio TEXT NULL,
ADD COLUMN profile_picture VARCHAR(255) NULL,
ADD COLUMN github_link VARCHAR(255) NULL,
ADD COLUMN website VARCHAR(255) NULL;
