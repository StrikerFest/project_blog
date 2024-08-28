<?php
require_once '../env.php';
require $_ENV['AUTOLOAD'];

use inc\helpers\DB;

// Kết nối đến máy chủ MySQL
$conn = DB::db_connect();

// Cài đặt dữ liệu người dùng
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
            echo "Người dùng đã được thêm thành công.<br>";
        } else {
            echo "Lỗi khi thêm người dùng: " . $conn->error . "<br>";
        }
    }
}

// Cài đặt dữ liệu bài viết
$authors = [1, 2];
$editors = [3];
$statuses = ['draft', 'pending_approval', 'approved', 'published'];
$deleted_at = null;

for ($i = 1; $i <= 10; $i++) {
    $title = "Tiêu đề bài viết mẫu " . $i;
    $slug = strtolower(str_replace(' ', '-', $title));
    $content = "Đây là nội dung cho Tiêu đề bài viết mẫu " . $i . ". Nó chứa thông tin chi tiết về nhiều chủ đề khác nhau.";
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
        echo "Bài viết $i đã được thêm thành công!<br>";
    } else {
        echo "Lỗi khi thêm bài viết $i: " . $conn->error . "<br>";
    }
}

// Cài đặt dữ liệu danh mục
$sql = "SELECT count(*) as count FROM categories";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        for ($i = 1; $i <= 5; $i++) {
            $name = "Danh mục $i";
            $slug = strtolower(str_replace(' ', '-', $name));
            $status = 'enabled';
            $description = "Mô tả cho $name";
            $position = $i;

            // Gán ngẫu nhiên một số danh mục là đã bị xóa mềm
            $deleted_at = rand(0, 1) ? date('Y-m-d H:i:s', strtotime("-" . rand(1, 10) . " ngày")) : null;

            $sql = "INSERT INTO categories (name, slug, status, description, position, deleted_at) 
                    VALUES ('$name', '$slug', '$status', '$description', '$position', " . ($deleted_at ? "'$deleted_at'" : "NULL") . ")";

            if ($conn->query($sql) === TRUE) {
                echo "Danh mục $i đã được thêm thành công!<br>";
            } else {
                echo "Lỗi khi thêm danh mục $i: " . $conn->error . "<br>";
            }
        }
    }
}

// Cài đặt dữ liệu bài viết-danh mục
$sql = "REPLACE INTO post_categories (post_id, category_id) VALUES ";

for ($i = 1; $i <= 10; $i++) {
    $post_id = $i;
    $category_id = rand(1, 5);
    $sql .= "($post_id, $category_id),";
}

$sql = rtrim($sql, ",");

if ($conn->query($sql) === TRUE) {
    echo "Các mối quan hệ bài viết-danh mục đã được thêm/cập nhật thành công.<br>";
} else {
    echo "Lỗi khi thêm/cập nhật mối quan hệ bài viết-danh mục: " . $conn->error . "<br>";
}

// Cài đặt dữ liệu thẻ
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
            $deleted_at = (rand(0, 1)) ? date('Y-m-d H:i:s', strtotime("-" . rand(1, 10) . " ngày")) : null;
            $sql = "INSERT INTO tags (name, slug, status, position, deleted_at) VALUES 
                    ('{$tag['name']}', '{$tag['slug']}', '{$tag['status']}', 0, " . ($deleted_at ? "'$deleted_at'" : "NULL") . ")";
            if ($conn->query($sql) === TRUE) {
                echo "Thẻ '{$tag['name']}' đã được thêm thành công!<br>";
            } else {
                echo "Lỗi khi thêm thẻ '{$tag['name']}': " . $conn->error . "<br>";
            }
        }
    }
}

// Cài đặt dữ liệu bài viết-thẻ
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
    echo "Các mối quan hệ bài viết-thẻ đã được thêm/cập nhật thành công.<br>";
} else {
    echo "Lỗi khi thêm/cập nhật mối quan hệ bài viết-thẻ: " . $conn->error . "<br>";
}

// Cài đặt dữ liệu bình luận
for ($i = 1; $i <= 20; $i++) {
    $post_id = rand(1, 10);
    $user_id = rand(1, 4);
    $content = "Đây là một bình luận trên bài viết $post_id bởi người dùng $user_id.";

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ($post_id, $user_id, '$content')";

    if ($conn->query($sql) === TRUE) {
        echo "Bình luận $i đã được thêm thành công!<br>";
    } else {
        echo "Lỗi khi thêm bình luận $i: " . $conn->error . "<br>";
    }
}

// Cài đặt dữ liệu loại banner
$sql = "SELECT count(*) as count FROM banner_types";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO `banner_types` (`name`, `description`) VALUES
           ('Header', 'Banner hiển thị ở đầu trang'),
           ('Sidebar', 'Banner hiển thị trên thanh bên của trang'),
           ('Footer', 'Banner hiển thị ở cuối trang'),
           ('Inline', 'Banner hiển thị trong nội dung của trang')";

        if ($conn->query($sql) === TRUE) {
            echo "Các loại banner đã được thêm thành công!<br>";
        } else {
            echo "Lỗi khi thêm các loại banner: " . $conn->error . "<br>";
        }
    }
}

// Cài đặt dữ liệu banner
$banners = [
    [
        'title' => 'Banner Header 1',
        'image_path' => '/assets/images/line.jpg',
        'text' => 'Khám phá các thiết bị mới!',
        'type_id' => 1
    ],
    [
        'title' => 'Banner Sidebar 1',
        'image_path' => '/assets/images/line.jpg',
        'text' => 'Khám phá các thiết bị mới!',
        'type_id' => 2
    ],
    [
        'title' => 'Banner Footer 1',
        'image_path' => '/assets/images/line.jpg',
        'text' => 'Khám phá các thiết bị mới!',
        'type_id' => 3
    ],
    [
        'title' => 'Banner Inline 1',
        'image_path' => '/assets/images/line.jpg',
        'text' => 'Khám phá các thiết bị mới!',
        'type_id' => 4
    ],
];

foreach ($banners as $banner) {
    $title = $banner['title'];
    $image_path = $banner['image_path'];
    $text = $banner['text'];
    $type_id = $banner['type_id'];

    $sql = "INSERT INTO banners (title, image_path, text, type_id, position) VALUES ('$title', '$image_path', '$text', $type_id, 0)";
    if ($conn->query($sql) === TRUE) {
        echo "Banner '{$banner['title']}' đã được thêm thành công!<br>";
    } else {
        echo "Lỗi khi thêm banner '{$banner['title']}': " . $conn->error . "<br>";
    }
}

// Đóng kết nối
$conn->close();
?>
