<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use database\DB;

// Connect to MySQL server
$conn = DB::db_connect();

// SQL script to create database and table
$sql = "
    -- Create database if not exists
    CREATE DATABASE IF NOT EXISTS `project_blog`;

    -- Use the created database
    USE `project_blog`;

    -- Create posts table
    CREATE TABLE IF NOT EXISTS `posts` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL
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
