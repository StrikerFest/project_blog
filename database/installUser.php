<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use database\DB;

// Connect to MySQL server
$conn = DB::db_connect();

// SQL script to create database and table
$sql = "
    USE `project_blog`;

    CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(60) NOT NULL,
        `type` SMALLINT
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
