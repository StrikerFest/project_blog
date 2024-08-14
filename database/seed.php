<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use inc\helpers\DB;

// Connect to MySQL server
$conn = DB::db_connect();

// Seed users
$sql = "SELECT count(*) as count FROM users";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES
        ('admin', 'admin@gmail.com', '" . password_hash('adminpass', PASSWORD_BCRYPT) . "', 'admin'),
        ('author', 'author@gmail.com', '" . password_hash('authorpass', PASSWORD_BCRYPT) . "', 'author'),
        ('editor', 'editor@gmail.com', '" . password_hash('editorpass', PASSWORD_BCRYPT) . "', 'editor'),
        ('user', 'user@gmail.com', '" . password_hash('userpass', PASSWORD_BCRYPT) . "', 'reader')";
        if ($conn->query($sql) === TRUE) {
            echo "Users inserted successfully.<br>";
        } else {
            echo "Error inserting users: " . $conn->error . "<br>";
        }
    }
}

// Seed posts
$authors = [1, 2];
$editors = [3];
$statuses = ['draft', 'pending_approval', 'approved', 'published'];
$deleted_at = null;

for ($i = 1; $i <= 10; $i++) {
    $title = "Sample Post Title " . $i;
    $slug = strtolower(str_replace(' ', '-', $title));
    $content = "This is the content for Sample Post Title " . $i . ". It contains detailed information on various topics.";
    $banner_path = "/assets/uploads/sample_banner_$i.jpg";
    $thumbnail_path = "/assets/uploads/sample_thumbnail_$i.jpg";
    $likes = rand(0, 100);
    $status = $statuses[array_rand($statuses)];
    $author = $authors[array_rand($authors)];
    $editor_id = ($status !== 'draft') ? $editors[array_rand($editors)] : null;
    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

    // Randomly assign some posts as soft-deleted
    if (rand(0, 1)) {
        $deleted_at = date('Y-m-d H:i:s', strtotime("-" . rand(1, 10) . " days"));
    } else {
        $deleted_at = null;
    }

    $sql = "INSERT INTO posts (author_id, editor_id, title, slug, content, banner_path, thumbnail_path, likes, status, published_at, deleted_at) 
            VALUES ('$author', " . ($editor_id ? "'$editor_id'" : "NULL") . ", '$title', '$slug', '$content', '$banner_path', '$thumbnail_path', $likes, '$status', " . ($published_at ? "'$published_at'" : "NULL") . ", " . ($deleted_at ? "'$deleted_at'" : "NULL") . ")";

    if ($conn->query($sql) === TRUE) {
        echo "Post $i inserted successfully!<br>";
    } else {
        echo "Error inserting post $i: " . $conn->error . "<br>";
    }
}

// Seed categories
$sql = "SELECT count(*) as count FROM categories";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO categories (name, slug, status, description) VALUES
            ('Technology', 'technology', 'enabled', 'Latest updates on technology.'),
            ('Sports', 'sports', 'enabled', 'All about sports events and news.'),
            ('Travel', 'travel', 'enabled', 'Travel tips and destination guides.'),
            ('Health', 'health', 'enabled', 'Health advice and wellness tips.'),
            ('Food', 'food', 'enabled', 'Delicious recipes and food reviews.')";
        if ($conn->query($sql) === TRUE) {
            echo "Categories inserted successfully.<br>";
        } else {
            echo "Error inserting categories: " . $conn->error . "<br>";
        }
    }
}

// Seed post_categories
$sql = "REPLACE INTO post_categories (post_id, category_id) VALUES ";

for ($i = 1; $i <= 10; $i++) {
    $post_id = $i;
    $category_id = rand(1, 5);
    $sql .= "($post_id, $category_id),";
}

$sql = rtrim($sql, ",");

if ($conn->query($sql) === TRUE) {
    echo "Post-Category associations inserted/updated successfully.<br>";
} else {
    echo "Error inserting/updating post-category associations: " . $conn->error . "<br>";
}

// Seed tags
$sql = "SELECT count(*) as count FROM tags";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO tags (name, slug, status) VALUES
            ('AI', 'ai', 'enabled'),
            ('Machine Learning', 'machine-learning', 'enabled'),
            ('Blockchain', 'blockchain', 'enabled'),
            ('Nutrition', 'nutrition', 'enabled'),
            ('Fitness', 'fitness', 'enabled')";
        if ($conn->query($sql) === TRUE) {
            echo "Tags inserted successfully.<br>";
        } else {
            echo "Error inserting tags: " . $conn->error . "<br>";
        }
    }
}

// Seed post_tags
$sql = "REPLACE INTO post_tags (post_id, tag_id) VALUES ";

for ($i = 1; $i <= 10; $i++) {
    $post_id = $i;
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

// Seed comments
for ($i = 1; $i <= 20; $i++) {
    $post_id = rand(1, 10);
    $user_id = rand(1, 4);
    $content = "This is a comment on post $post_id by user $user_id.";

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ($post_id, $user_id, '$content')";

    if ($conn->query($sql) === TRUE) {
        echo "Comment $i inserted successfully!<br>";
    } else {
        echo "Error inserting comment $i: " . $conn->error . "<br>";
    }
}

// Close MySQL connection
$conn->close();
