<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use inc\helpers\DB;

// Connect to MySQL server
$conn = DB::db_connect();

// SQL script to create database and table
$sql = "
    
    DROP DATABASE project_blog;
    
    -- Create database if not exists
    CREATE DATABASE IF NOT EXISTS `project_blog`;

    -- Use the created database
    USE `project_blog`;

    CREATE TABLE IF NOT EXISTS `users` (
        `user_id` INT AUTO_INCREMENT PRIMARY KEY,
        `email` VARCHAR(255) NOT NULL,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(60) NOT NULL,
        `role` ENUM('admin', 'author', 'editor', 'reader') NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `bio` TEXT DEFAULT NULL,
        `profile_image` VARCHAR(255) DEFAULT NULL,
        `deleted_at` TIMESTAMP NULL DEFAULT NULL
    );
    
    CREATE TABLE IF NOT EXISTS `posts` (
        `post_id` INT AUTO_INCREMENT PRIMARY KEY,
        `author_id` INT,
        `editor_id` INT DEFAULT NULL,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `content` TEXT NOT NULL,
        `banner_path` VARCHAR(255) DEFAULT NULL,
        `thumbnail_path` VARCHAR(255) DEFAULT NULL,
        `likes` INT DEFAULT 0,
        `status` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
        `published_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `deleted_at` TIMESTAMP NULL DEFAULT NULL,
        FOREIGN KEY (author_id) REFERENCES users(user_id),
        FOREIGN KEY (editor_id) REFERENCES users(user_id)
    );

    CREATE TABLE categories (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(150) NOT NULL,
        status ENUM('enabled', 'disabled') NOT NULL,
        description TEXT,
        position INT NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `deleted_at` TIMESTAMP NULL DEFAULT NULL
    );
    
    CREATE TABLE IF NOT EXISTS `post_categories` (
        `post_id` INT,
        `category_id` INT,
        PRIMARY KEY (post_id, category_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id),
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
    );

    CREATE TABLE tags (
        tag_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(150) NOT NULL,
        status ENUM('enabled', 'disabled') NOT NULL,
        position INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL DEFAULT NULL
    );
    
    CREATE TABLE IF NOT EXISTS post_tags (
        post_id INT,
        tag_id INT,
        PRIMARY KEY (post_id, tag_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id),
        FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
    );
    
    CREATE TABLE comments (
        comment_id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT,
        user_id INT,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(post_id),
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    );
    
    CREATE TABLE IF NOT EXISTS `post_likes` (
        `like_id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `post_id` INT NOT NULL,
        `liked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
        FOREIGN KEY (`post_id`) REFERENCES `posts`(`post_id`),
        UNIQUE (`user_id`, `post_id`)
    );

-- Create the banner_types table with deleted_at column
CREATE TABLE IF NOT EXISTS `banner_types` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

-- Create the banners table with deleted_at column
CREATE TABLE IF NOT EXISTS `banners` (
     `id` INT AUTO_INCREMENT PRIMARY KEY,
     `title` VARCHAR(255) NOT NULL,
     `image_path` VARCHAR(255) NOT NULL,
     `text` TEXT,
     `link` VARCHAR(255),
     `start_date` DATETIME NOT NULL,
     `end_date` DATETIME NOT NULL,
     `is_active` BOOLEAN DEFAULT TRUE,
     `type_id` INT,
     `position` INT NOT NULL DEFAULT 0,
     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     `deleted_at` TIMESTAMP NULL DEFAULT NULL,
     FOREIGN KEY (`type_id`) REFERENCES `banner_types`(`id`)
);

-- Create the approval_logs table
CREATE TABLE IF NOT EXISTS `approval_logs` (
    `approval_id` INT AUTO_INCREMENT PRIMARY KEY,
    `post_id` INT,
    `user_id` INT,
    `status_from` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
    `status_to` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
    `reason` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`post_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);


";

// Execute SQL script
if ($conn->multi_query($sql) === TRUE) {
    echo "Database and table created successfully.";
} else {
    echo "Error creating database and table: " . $conn->error;
}

// Close MySQL connection
$conn->close();
