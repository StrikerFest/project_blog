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


// Generate user
$sql = "SELECT count(*) as count FROM users";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES
        ('admin', 'admin@gmail.com', '$2a$12$9msGes.EQ1t3kEvK/HnWi.tb8O2wDtVLYXcvRGE/IhV2DgEoV0A4a', 'admin'),
        ('author', 'author@gmail.com', '$2a$12$9msGes.EQ1t3kEvK/HnWi.tb8O2wDtVLYXcvRGE/IhV2DgEoV0A4a', 'author'),
        ('editor', 'editor@gmail.com', '$2a$12$9msGes.EQ1t3kEvK/HnWi.tb8O2wDtVLYXcvRGE/IhV2DgEoV0A4a', 'editor'),
        ('user', 'user@gmail.com', '$2a$12$89RY.tomk.SecxkYTb4E6uQEAd7yWSTrLU4VFV1wP456XsQhCxVcO', 'reader')";
        if ($conn->query($sql) === TRUE) {
            echo "User inserted successfully.<br>";
        } else {
            echo "Error inserting user: " . $conn->error . "<br>";
        }
    }
}

// Generate category
$sql = "SELECT count(*) as count FROM categories";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO categories (name, status, description) VALUES
            ('Công nghệ', 'enabled', '" . generateRandomString(10) . "'),
            ('Bóng đá', 'enabled', '" . generateRandomString(10) . "'),
            ('Du lịch', 'enabled', '" . generateRandomString(10) . "'),
            ('Sức khỏe', 'enabled', '" . generateRandomString(10) . "'),
            ('Ẩm thực', 'enabled', '" . generateRandomString(10) . "')";
        print($sql);
        if ($conn->query($sql) === TRUE) {
            echo "Category inserted successfully.<br>";
        } else {
            echo "Error inserting category: " . $conn->error . "<br>";
        }
    }
}

// Generate post_categories data
$sql = "REPLACE INTO post_categories (post_id, category_id) VALUES ";

for ($i = 0; $i < 20; $i++) {
    $post_id = rand(1, 10);
    $category_id = rand(1, 5);
    $sql .= "($post_id, $category_id),";
}

$sql = rtrim($sql, ",");

if ($conn->query($sql) === TRUE) {
    echo "Post-Category associations inserted/updated successfully.<br>";
} else {
    echo "Error inserting/updating post-category associations: " . $conn->error . "<br>";
}

// Generate tag
$sql = "SELECT count(*) as count FROM tags";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO tags (name, status) VALUES
            ('Apple', 'enabled'),
            ('Nike', 'enabled'),
            ('Coca-Cola', 'enabled'),
            ('Samsung', 'enabled'),
            ('Google', 'enabled')";
        if ($conn->query($sql) === TRUE) {
            echo "Tag inserted successfully.<br>";
        } else {
            echo "Error inserting tag: " . $conn->error . "<br>";
        }
    }
}

// Generate post_tags data using REPLACE INTO

$sql = "REPLACE INTO post_tags (post_id, tag_id) VALUES ";

for ($i = 0; $i < 20; $i++) {
    $post_id = rand(1, 10);
    $num_tags = rand(1, 3);
    
    for ($j = 0; $j < $num_tags; $j++) {
        $tag_id = rand(1, 5);
        $sql .= "($post_id, $tag_id),";
    }
}

$sql = rtrim($sql, ",");

if ($conn->query($sql) === TRUE) {
    echo "Post-Tag associations inserted/updated successfully.<br>";
} else {
    echo "Error inserting/updating post-tag associations: " . $conn->error . "<br>";
}

// Close MySQL connection
$conn->close();

// Function to generate a random string
function generateRandomString($length = 10): string
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
