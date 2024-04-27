<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use database\DB;

// Connect to MySQL server
$conn = DB::db_connect();

// Generate 10 random posts
for ($i = 1; $i <= 10; $i++) {
    $title = generateRandomString(10); // Generate a random title
    $content = generateRandomString(50); // Generate a random content

    // Insert post into the database
    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        echo "Post inserted successfully.<br>";
    } else {
        echo "Error inserting post: " . $conn->error . "<br>";
    }
}

// Close MySQL connection
$conn->close();

// Function to generate a random string
function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

