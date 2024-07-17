<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use database\DB;

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
        `role` ENUM('admin', 'author', 'editor','reader') NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    CREATE TABLE IF NOT EXISTS `posts` (
        `post_id` INT AUTO_INCREMENT PRIMARY KEY,
        `author_id` INT,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `banner_path` VARCHAR(255) DEFAULT NULL,
        `thumbnail_path` VARCHAR(255) DEFAULT NULL,
        `likes` INT DEFAULT 0,
        `status` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
        `published_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES users(user_id)
    );

    CREATE TABLE IF NOT EXISTS `categories` (
        `category_id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `status` ENUM('enabled', 'disabled') NOT NULL,
        `description` TEXT
    );
    
    CREATE TABLE IF NOT EXISTS `post_categories` (
        `post_id` INT,
        `category_id` INT,
        PRIMARY KEY (post_id, category_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id),
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
    );

    CREATE TABLE IF NOT EXISTS `tags` (
        `tag_id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `status` ENUM('enabled', 'disabled') NOT NULL
    );
    
    CREATE TABLE IF NOT EXISTS post_tags (
        post_id INT,
        tag_id INT,
        PRIMARY KEY (post_id, tag_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id),
        FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
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
