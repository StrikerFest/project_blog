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
    $banner_path = "/assets/images/placeholder-banner.webp";
    $thumbnail_path = "/assets/images/placeholder-thumbnail.webp";
    $likes = rand(0, 100);
    $status = 'published';
    $author = $authors[array_rand($authors)];
    $editor_id = $editors[array_rand($editors)];
    $published_at = date('Y-m-d H:i:s');
    $deleted_at = null;

    $sql = "INSERT INTO posts (author_id, editor_id, title, slug, content, banner_path, thumbnail_path, likes, status, published_at, deleted_at) 
            VALUES ('$author', '$editor_id', '$title', '$slug', '$content', '$banner_path', '$thumbnail_path', $likes, '$status', '$published_at', NULL)";

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
        for ($i = 1; $i <= 5; $i++) {
            $name = "Category $i";
            $slug = strtolower(str_replace(' ', '-', $name));
            $status = 'enabled';
            $description = "Description for $name";
            $position = $i;

            // Randomly assign some categories as soft-deleted
            $deleted_at = rand(0, 1) ? date('Y-m-d H:i:s', strtotime("-" . rand(1, 10) . " days")) : null;

            $sql = "INSERT INTO categories (name, slug, status, description, position, deleted_at) 
                    VALUES ('$name', '$slug', '$status', '$description', '$position', " . ($deleted_at ? "'$deleted_at'" : "NULL") . ")";

            if ($conn->query($sql) === TRUE) {
                echo "Category $i inserted successfully!<br>";
            } else {
                echo "Error inserting category $i: " . $conn->error . "<br>";
            }
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
        $tags = [
            ['name' => 'AI', 'slug' => 'ai', 'status' => 'enabled'],
            ['name' => 'Machine Learning', 'slug' => 'machine-learning', 'status' => 'enabled'],
            ['name' => 'Blockchain', 'slug' => 'blockchain', 'status' => 'enabled'],
            ['name' => 'Nutrition', 'slug' => 'nutrition', 'status' => 'enabled'],
            ['name' => 'Fitness', 'slug' => 'fitness', 'status' => 'enabled']
        ];

        foreach ($tags as $tag) {
            $deleted_at = (rand(0, 1)) ? date('Y-m-d H:i:s', strtotime("-" . rand(1, 10) . " days")) : null;
            $sql = "INSERT INTO tags (name, slug, status, position, deleted_at) VALUES 
                    ('{$tag['name']}', '{$tag['slug']}', '{$tag['status']}', 0, " . ($deleted_at ? "'$deleted_at'" : "NULL") . ")";
            if ($conn->query($sql) === TRUE) {
                echo "Tag '{$tag['name']}' inserted successfully!<br>";
            } else {
                echo "Error inserting tag '{$tag['name']}': " . $conn->error . "<br>";
            }
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

// Seed banner types
$sql = "SELECT count(*) as count FROM banner_types";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO `banner_types` (`name`, `description`) VALUES
           ('Header', 'Banners displayed at the top of the page'),
           ('Sidebar', 'Banners displayed on the sidebar of the page'),
           ('Footer', 'Banners displayed at the bottom of the page'),
           ('Inline', 'Banners displayed within the content of the page')";

        if ($conn->query($sql) === TRUE) {
            echo "Banner types inserted successfully!<br>";
        } else {
            echo "Error inserting banner types: " . $conn->error . "<br>";
        }
    }
}

// Seed banners
$banners = [
    [
        'title' => 'Header Banner 1',
        'image_path' => '/assets/images/line.jpg',
        'text' => 'Discover the newest gadgets and electronics on our blog. Click here to read more!',
        'link' => 'https://techblog.com/gadgets',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-12-31 23:59:59',
        'is_active' => 1,
        'type_id' => 1, // Header
        'position' => 0
    ],
    [
        'title' => 'Subscribe to Our Newsletter',
        'image_path' => '/assets/images/1270x300_placeholder_banner.webp',
        'text' => 'Stay updated with the latest tech news. Subscribe to our newsletter!',
        'link' => 'https://techblog.com/newsletter',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-12-31 23:59:59',
        'is_active' => 1,
        'type_id' => 1, // Header
        'position' => 1
    ],
    [
        'title' => 'Top 10 Coding Practices',
        'image_path' => '/assets/images/300x1270_placeholder_banner.webp',
        'text' => 'Enhance your coding skills with our top 10 coding practices. Click here to learn more!',
        'link' => 'https://techblog.com/coding-practices',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-12-31 23:59:59',
        'is_active' => 1,
        'type_id' => 2, // Sidebar
        'position' => 0
    ],
    [
        'title' => 'Cybersecurity Tips',
        'image_path' => '/assets/images/1270x300_placeholder_banner.webp',
        'text' => 'Protect yourself online with our top cybersecurity tips. Click here to read more!',
        'link' => 'https://techblog.com/cybersecurity-tips',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-10-31 23:59:59',
        'is_active' => 1,
        'type_id' => 2, // Sidebar
        'position' => 1
    ],
    [
        'title' => 'Join Our Webinar',
        'image_path' => '/assets/images/1270x300_placeholder_banner.webp',
        'text' => 'Join our free webinar on the future of AI. Click here to register!',
        'link' => 'https://techblog.com/webinar',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-08-31 23:59:59',
        'is_active' => 1,
        'type_id' => 3, // Footer
        'position' => 0
    ],
    [
        'title' => 'Latest Tech Reviews',
        'image_path' => '/assets/images/1270x300_placeholder_banner.webp',
        'text' => 'Read our latest reviews on the newest tech products. Click here to check them out!',
        'link' => 'https://techblog.com/reviews',
        'start_date' => '2024-08-01 00:00:00',
        'end_date' => '2024-11-30 23:59:59',
        'is_active' => 1,
        'type_id' => 4, // Inline
        'position' => 0
    ],
];

foreach ($banners as $banner) {
    $sql = "INSERT INTO banners (title, image_path, text, link, start_date, end_date, is_active, type_id, position) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssiis', $banner['title'], $banner['image_path'], $banner['text'], $banner['link'], $banner['start_date'], $banner['end_date'], $banner['is_active'], $banner['type_id'], $banner['position']);
    $stmt->execute();
    $stmt->close();
}

// Close MySQL connection
$conn->close();
