<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

class Tag
{
    public static function save_tag(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $status = $_POST['status'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $position = $_POST['position'] ?? '';

            $slug = self::generateSlug($slug);
            $conn = DB::db_connect();

            // Validate unique name and slug
            $errors = [];

            // Check for unique name
            $sql = "SELECT COUNT(*) as count FROM tags WHERE name = ? AND tag_id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                $errors[] = "The tag name '$name' is already taken.";
            }

            // Check for unique slug
            $sql = "SELECT COUNT(*) as count FROM tags WHERE slug = ? AND tag_id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $slug, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                $errors[] = "The slug '$slug' is already taken.";
            }

            // If there are validation errors, store them in session and redirect back
            if (!empty($errors)) {
                $_SESSION['tag_errors'] = $errors;
                $_SESSION['tag_data'] = $_POST;
                header("Location: " . ($_POST['id'] ? "/admin/tag/edit?id=" . $_POST['id'] : "/admin/tag/create"));
                exit();
            }

            // If no errors, proceed with saving
            if ($id == '') {
                $sql = "INSERT INTO tags (name, status, slug, position) VALUES (?,?,?,?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssss", $name, $status, $slug, $position);
            } else {
                $sql = "UPDATE tags SET name = ?, status = ?, slug = ?, position = ?, updated_at = CURRENT_TIMESTAMP WHERE tag_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssssi", $name, $status, $slug, $position, $id);
            }
            $statement->execute();

            if (!$statement->errno) {
                $conn->close();
                header("Location: /admin/tag");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->close();
            }
        }
    }

    private static function generateSlug($name): string
    {
        // Convert to lowercase
        $slug = strtolower($name);
        // Remove non-alphanumeric characters
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        // Remove multiple dashes
        $slug = preg_replace('/-+/', '-', $slug);
        // Trim dashes from ends
        return trim($slug, '-');
    }

    public static function getTags($includeDeleted = false): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags";
        if (!$includeDeleted) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY tag_id ASC";
        $result = $conn->query($sql);
        $tags = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tags[] = $row;
            }
        }
        $conn->close();
        return $tags;
    }

    public static function getTagById($id): bool|array|null
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags WHERE tag_id = ? AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tag = null;
        if ($result->num_rows == 1) {
            $tag = $result->fetch_assoc();
        }
        $stmt->close();
        $conn->close();
        return $tag;
    }

    public static function deleteTag($id): void
    {
        $conn = DB::db_connect();
        $sql = "UPDATE tags SET deleted_at = CURRENT_TIMESTAMP WHERE tag_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function recoverTag($id): void
    {
        $conn = DB::db_connect();
        $sql = "UPDATE tags SET deleted_at = NULL WHERE tag_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function getPostTagIds($postId): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT t.tag_id, t.name, t.slug, t.status
            FROM tags t
            INNER JOIN post_tags pt ON t.tag_id = pt.tag_id
            WHERE pt.post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $result = Common::getArrayBySQL($sql, $stmt);
        $conn->close();

        return $result;
    }

    public static function getTagsByPosition($limit): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT tag_id FROM tags ORDER BY position , tag_id LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $tagIds = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tagIds[] = $row['tag_id'];
            }
        }
        $stmt->close();
        $conn->close();
        return $tagIds;
    }

    public static function getTagIdBySlug($slug): ?int
    {
        $conn = DB::db_connect();
        $sql = "SELECT tag_id FROM tags WHERE slug = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $stmt->bind_result($tag_id);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        return $tag_id ?: null;
    }

    public static function getFilteredTags($filters = [], $includeDeleted = false): array
    {
        $sql = "SELECT * FROM tags WHERE 1=1";

        $params = [];
        $types = '';

        // Apply filters
        if (!empty($filters['id'])) {
            $sql .= " AND tag_id = ?";
            $params[] = $filters['id'];
            $types .= 'i';
        }

        if (!empty($filters['name'])) {
            $sql .= " AND name LIKE ?";
            $params[] = '%' . $filters['name'] . '%';
            $types .= 's';
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
            $types .= 's';
        }

        if (!empty($filters['position'])) {
            $sql .= " AND position = ?";
            $params[] = $filters['position'];
            $types .= 'i';
        }

        // Handle deleted tags
        if (!$includeDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $sql .= " ORDER BY updated_at DESC";

        return DB::fetchAllRows($sql, $params, $types);
    }
}
