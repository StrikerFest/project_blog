<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

class Post
{
    public static function save_post(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $slug = self::generateSlug($title);
            $content = $_POST['content'] ?? '';
            $author_id = $_POST['author_id'] ?? '';
            $editor_id = $_POST['editor_id'] ?? null;
            $categories = $_POST['categories'] ?? [];
            $tags = $_POST['tags'] ?? [];
            $status = $_POST['status'] ?? '';
            $reason = $_POST['reason'] ?? ''; // Capture the reason for status change

            $upload_dir = $_ENV['UPLOAD_DIR'];
            $relative_upload_dir = '/assets/uploads/';
            $thumbnail_path = '';
            $banner_path = '';

            if (!empty($_FILES['thumbnail']['name'])) {
                $thumbnail_filename = basename($_FILES['thumbnail']['name']);
                $thumbnail_full_path = $upload_dir . $thumbnail_filename;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_full_path);
                $thumbnail_path = $relative_upload_dir . $thumbnail_filename;
            }

            if (!empty($_FILES['banner']['name'])) {
                $banner_filename = basename($_FILES['banner']['name']);
                $banner_full_path = $upload_dir . $banner_filename;
                move_uploaded_file($_FILES['banner']['tmp_name'], $banner_full_path);
                $banner_path = $relative_upload_dir . $banner_filename;
            }

            $conn = DB::db_connect();
            $oldStatus = null;

            if ($id == '') {
                $sql = "INSERT INTO posts (title, slug, content, author_id, editor_id, status, thumbnail_path, banner_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssssssss", $title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path);
            } else {
                // Get the old status before updating
                $post = self::getPostById($id);
                $oldStatus = $post['status'] ?? null;

                $sql = "UPDATE posts SET title = ?, slug = ?, content = ?, author_id = ?, editor_id = ?, status = ?, thumbnail_path = ?, banner_path = ? WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssssssssi", $title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path, $id);
            }

            $statement->execute();

            if (!$statement->errno) {
                if ($id == '') {
                    $id = $statement->insert_id;
                }

                // Log status change if status is updated and reason is provided
                if ($oldStatus !== $status && !empty($reason)) {
                    self::logStatusChange($id, $author_id, $oldStatus, $status, $reason);

                    // Send email notification to the author
                    $author = self::getUserById($author_id); // Assuming a function that fetches user data
                    $authorEmail = $author['email'] ?? $_ENV['DEFAULT_FROM_EMAIL']; // Replace with appropriate email handling

                    Common::sendEmail(
                        $authorEmail,
                        'Post Status Changed',
                        "The status of your post titled '$title' (ID: $id) has changed from '$oldStatus' to '$status'.\n\nReason: $reason"
                    );

                    // Send email notification to the editor
                    if ($editor_id) {
                        $editor = self::getUserById($editor_id); // Assuming a function that fetches user data
                        $editorEmail = $editor['email'] ?? $_ENV['DEFAULT_FROM_EMAIL']; // Replace with appropriate email handling

                        Common::sendEmail(
                            $editorEmail,
                            'Post Status Changed',
                            "The status of the post titled '$title' (ID: $id) that you are editing has changed from '$oldStatus' to '$status'.\n\nReason: $reason"
                        );
                    }
                }

                // Handle categories
                $sql = "DELETE FROM post_categories WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("s", $id);
                $statement->execute();

                $sql = "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                foreach ($categories as $category_id) {
                    $statement->bind_param("ss", $id, $category_id);
                    $statement->execute();
                }

                // Handle tags
                $sql = "DELETE FROM post_tags WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("s", $id);
                $statement->execute();

                $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                foreach ($tags as $tag_id) {
                    $statement->bind_param("ss", $id, $tag_id);
                    $statement->execute();
                }

                $conn->close();
                header("Location: /admin/post");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->close();
            }
        }
    }

    private static function getUserById($user_id)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user;
    }

    private static function logStatusChange($post_id, $user_id, $status_from, $status_to, $reason): void
    {
        $approvalLog = new ApprovalLog();
        $approvalLog->createLog($post_id, $user_id, $status_from, $status_to, $reason);
    }


    private static function generateSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    public static function getPosts($sortOrder = 'asc', $published = true, $includeDeleted = false): array
    {
        $conn = DB::db_connect();

        // Base SQL query
        $sql = "SELECT * FROM posts";

        // Conditionally add the WHERE clause based on whether deleted posts should be included
        if (!$includeDeleted) {
            $sql .= " WHERE deleted_at IS NULL";
        } else {
            $sql .= " WHERE 1=1"; // Dummy condition to make appending other conditions easier
        }

        // Add the condition for published status if necessary
        if ($published) {
            $sql .= " AND status = 'published'";
        }

        // Append the ORDER BY clause
        $sql .= " ORDER BY post_id $sortOrder";

        // Execute the query
        $result = $conn->query($sql);
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }

        // Close the connection
        $conn->close();

        return $posts;
    }


    public static function getPostById($id): bool|array|null
    {
        if (!isset($id)) {
            return null;
        }

        $conn = DB::db_connect();
        $sql = "SELECT * FROM posts WHERE post_id = ? AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = null;
        if ($result->num_rows == 1) {
            $post = $result->fetch_assoc();
        }
        $stmt->close();
        $conn->close();
        return $post;
    }

    public static function softDeletePost($id): void
    {
        $conn = DB::db_connect();
        $sql = "UPDATE posts SET deleted_at = NOW() WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function restorePost($id): void
    {
        $conn = DB::db_connect();
        $sql = "UPDATE posts SET deleted_at = NULL WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function getPostsByCategoryId($categoryId, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
        ORDER BY p.updated_at DESC, p.post_id DESC
        LIMIT ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function getPostsByCategoryIdWithPagination($categoryId, $offset, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
        ORDER BY p.post_id DESC
        LIMIT ?, ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $categoryId, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function countPostsByCategoryId($categoryId): int
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT COUNT(*) as total
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $total;
    }

    public static function deletePost($id): void
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM posts WHERE post_id=$id";
        $conn->query($sql);
        $conn->close();
    }

    public static function getPostsByTagId($tagId, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_tags pc ON p.post_id = pc.post_id
        WHERE pc.tag_id = ?
        ORDER BY p.updated_at DESC, p.post_id DESC
        LIMIT ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $tagId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function getPostsByTagIdWithPagination($tagId, $offset, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_tags pc ON p.post_id = pc.post_id
        WHERE pc.tag_id = ?
        ORDER BY p.post_id DESC
        LIMIT ?, ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $tagId, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function countPostsByTagId($tagId): int
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT COUNT(*) as total
        FROM posts p
        INNER JOIN post_tags pc ON p.post_id = pc.post_id
        WHERE pc.tag_id = ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $total;
    }

    public static function searchPostsCategoriesTags($query): array
    {
        $conn = DB::db_connect();
        $searchQuery = '%' . $conn->real_escape_string($query) . '%';

        $sql = "SELECT * FROM posts WHERE (title LIKE ? OR content LIKE ?) AND status = 'published' ORDER BY post_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();

        $sql = "SELECT * FROM categories WHERE name LIKE ? OR description LIKE ? ORDER BY category_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        $stmt->close();

        $sql = "SELECT * FROM tags WHERE name LIKE ? OR slug LIKE ? ORDER BY tag_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $tags = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tags[] = $row;
            }
        }
        $stmt->close();
        $conn->close();

        return [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
        ];
    }
}
