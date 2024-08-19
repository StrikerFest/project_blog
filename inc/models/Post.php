<?php

namespace inc\models;

use inc\helpers\admin\PostSave as PostSaveHelper;
use inc\helpers\DB as DBHelper;

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
            $reason = $_POST['reason'] ?? '';

            // Handle file uploads
            $thumbnail_path = PostSaveHelper::handleFileUpload($_FILES['thumbnail'] ?? null, 'thumbnail');
            $banner_path = PostSaveHelper::handleFileUpload($_FILES['banner'] ?? null, 'banner');

            $oldStatus = $id ? PostSaveHelper::getPostOldStatus($id) : null;

            $id = PostSaveHelper::saveOrUpdatePost($id, $title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path);

            if ($oldStatus !== $status && !empty($reason)) {
                self::logStatusChange($id, $author_id, $oldStatus, $status, $reason);
                PostSaveHelper::sendStatusChangeEmail($author_id, $editor_id, $title, $id, $oldStatus, $status, $reason);
            }

            PostSaveHelper::handlePostCategories($id, $categories);
            PostSaveHelper::handlePostTags($id, $tags);

            header("Location: /admin/post");
            exit();
        }
    }

    public static function getUserById($user_id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        return DBHelper::fetchSingleRow($sql, [$user_id], "i");
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
        $sql = "SELECT * FROM posts";
        $conditions = [];

        if (!$includeDeleted) {
            $conditions[] = "deleted_at IS NULL";
        }

        if ($published) {
            $conditions[] = "status = 'published'";
        }

        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY post_id $sortOrder";

        return DBHelper::fetchAllRows($sql);
    }

    public static function getPostById($id): bool|array|null
    {
        if (!isset($id)) {
            return null;
        }

        $sql = "SELECT * FROM posts WHERE post_id = ? AND deleted_at IS NULL";
        return DBHelper::fetchSingleRow($sql, [$id], "i");
    }

    public static function softDeletePost($id): void
    {
        $sql = "UPDATE posts SET deleted_at = NOW() WHERE post_id = ?";
        DBHelper::executeQuery($sql, [$id], "i");
    }

    public static function restorePost($id): void
    {
        $sql = "UPDATE posts SET deleted_at = NULL WHERE post_id = ?";
        DBHelper::executeQuery($sql, [$id], "i");
    }

    public static function getPostsByCategoryId($categoryId, $limit = 3): array
    {
        $sql = "
            SELECT p.*
            FROM posts p
            INNER JOIN post_categories pc ON p.post_id = pc.post_id
            WHERE pc.category_id = ?
            ORDER BY p.updated_at DESC, p.post_id DESC
            LIMIT ?
        ";
        return DBHelper::fetchAllRows($sql, [$categoryId, $limit], "ii");
    }

    public static function getPostsByCategoryIdWithPagination($categoryId, $offset, $limit = 3): array
    {
        $sql = "
            SELECT p.*
            FROM posts p
            INNER JOIN post_categories pc ON p.post_id = pc.post_id
            WHERE pc.category_id = ?
            ORDER BY p.post_id DESC
            LIMIT ?, ?
        ";
        return DBHelper::fetchAllRows($sql, [$categoryId, $offset, $limit], "iii");
    }

    public static function countPostsByCategoryId($categoryId): int
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM posts p
            INNER JOIN post_categories pc ON p.post_id = pc.post_id
            WHERE pc.category_id = ?
        ";
        return DBHelper::fetchSingleValue($sql, [$categoryId], "i");
    }

    public static function deletePost($id): void
    {
        $sql = "DELETE FROM posts WHERE post_id = ?";
        DBHelper::executeQuery($sql, [$id], "i");
    }

    public static function getPostsByTagId($tagId, $limit = 3): array
    {
        $sql = "
            SELECT p.*
            FROM posts p
            INNER JOIN post_tags pc ON p.post_id = pc.post_id
            WHERE pc.tag_id = ?
            ORDER BY p.updated_at DESC, p.post_id DESC
            LIMIT ?
        ";
        return DBHelper::fetchAllRows($sql, [$tagId, $limit], "ii");
    }

    public static function getPostsByTagIdWithPagination($tagId, $offset, $limit = 3): array
    {
        $sql = "
            SELECT p.*
            FROM posts p
            INNER JOIN post_tags pc ON p.post_id = pc.post_id
            WHERE pc.tag_id = ?
            ORDER BY p.post_id DESC
            LIMIT ?, ?
        ";
        return DBHelper::fetchAllRows($sql, [$tagId, $offset, $limit], "iii");
    }

    public static function countPostsByTagId($tagId): int
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM posts p
            INNER JOIN post_tags pc ON p.post_id = pc.post_id
            WHERE pc.tag_id = ?
        ";
        return DBHelper::fetchSingleValue($sql, [$tagId], "i");
    }

    public static function searchPostsCategoriesTags($query): array
    {
        $searchQuery = '%' . $query . '%';

        $posts = DBHelper::fetchAllRows(
            "SELECT * FROM posts WHERE (title LIKE ? OR content LIKE ?) AND status = 'published' ORDER BY post_id DESC",
            [$searchQuery, $searchQuery],
            "ss"
        );

        $categories = DBHelper::fetchAllRows(
            "SELECT * FROM categories WHERE name LIKE ? OR description LIKE ? ORDER BY category_id DESC",
            [$searchQuery, $searchQuery],
            "ss"
        );

        $tags = DBHelper::fetchAllRows(
            "SELECT * FROM tags WHERE name LIKE ? OR slug LIKE ? ORDER BY tag_id DESC",
            [$searchQuery, $searchQuery],
            "ss"
        );

        return [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
        ];
    }
}
