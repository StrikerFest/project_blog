<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use database\DB;

// Connect to MySQL server
$conn = DB::db_connect();

$sql = "INSERT INTO users (username, password, type) VALUES
    ('admin', '$2a$12$9msGes.EQ1t3kEvK/HnWi.tb8O2wDtVLYXcvRGE/IhV2DgEoV0A4a', 1),
    ('user', '$2a$12$89RY.tomk.SecxkYTb4E6uQEAd7yWSTrLU4VFV1wP456XsQhCxVcO', 2)";
if ($conn->query($sql) === TRUE) {
    echo "Post inserted successfully.<br>";
} else {
    echo "Error inserting post: " . $conn->error . "<br>";
}
