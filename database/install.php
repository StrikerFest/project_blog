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

    CREATE TABLE categories (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(150) NOT NULL,
        status ENUM('enabled', 'disabled') NOT NULL,
        description TEXT,
        position INT NOT NULL DEFAULT 0
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
        position INT NOT NULL DEFAULT 0
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
     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     `deleted_at` TIMESTAMP NULL DEFAULT NULL,
     FOREIGN KEY (`type_id`) REFERENCES `banner_types`(`id`)
);

-- Insert data into banner_types table
INSERT INTO `banner_types` (`name`, `description`) VALUES
   ('Header', 'Banners displayed at the top of the page'),
   ('Sidebar', 'Banners displayed on the sidebar of the page'),
   ('Footer', 'Banners displayed at the bottom of the page'),
   ('Inline', 'Banners displayed within the content of the page');

-- Insert data into banners table
INSERT INTO `banners` (`title`, `image_path`, `text`, `link`, `start_date`, `end_date`, `is_active`, `type_id`) VALUES
    ('Get the Latest Tech Gadgets!', '/assets/uploads/banner_gadgets.jpg', 'Discover the newest gadgets and electronics on our blog. Click here to read more!', 'https://techblog.com/gadgets', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 1),
    ('Top 10 Coding Practices', '/assets/uploads/banner_coding.jpg', 'Enhance your coding skills with our top 10 coding practices. Click here to learn more!', 'https://techblog.com/coding-practices', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 2),
    ('Join Our Webinar', '/assets/uploads/banner_webinar.jpg', 'Join our free webinar on the future of AI. Click here to register!', 'https://techblog.com/webinar', '2024-08-01 00:00:00', '2024-08-31 23:59:59', TRUE, 3),
    ('Latest Tech Reviews', '/assets/uploads/banner_reviews.jpg', 'Read our latest reviews on the newest tech products. Click here to check them out!', 'https://techblog.com/reviews', '2024-08-01 00:00:00', '2024-11-30 23:59:59', TRUE, 4),
    ('Subscribe to Our Newsletter', '/assets/uploads/banner_newsletter.jpg', 'Stay updated with the latest tech news. Subscribe to our newsletter!', 'https://techblog.com/newsletter', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 1),
    ('Cybersecurity Tips', '/assets/uploads/banner_cybersecurity.jpg', 'Protect yourself online with our top cybersecurity tips. Click here to read more!', 'https://techblog.com/cybersecurity-tips', '2024-08-01 00:00:00', '2024-10-31 23:59:59', TRUE, 2);

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
