<?php

namespace inc\helpers\admin;

use inc\helpers\Common;
use inc\helpers\DB as DBHelper;
use inc\models\Post;

class PostSave
{
    /**
     * Handle file uploads and return the file path.
     */
    public static function handleFileUpload($file, $type): ?string
    {
        if ($file && isset($file['name']) && $file['error'] == UPLOAD_ERR_OK) {
            $upload_dir = $_ENV['UPLOAD_DIR'];
            $relative_upload_dir = '/assets/uploads/';
            $filename = basename($file['name']);
            $full_path = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $full_path)) {
                return $relative_upload_dir . $filename;
            }
        }

        return null;
    }

    /**
     * Get the old status of a post by its ID.
     */
    public static function getPostOldStatus($id): ?string
    {
        $post = Post::getPostById($id);
        return $post['status'] ?? null;
    }

    /**
     * Save or update a post in the database.
     * @throws \Exception
     */
    public static function saveOrUpdatePost($id, $title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path)
    {
        $published_at = null;

        if ($status === 'published') {
            $published_at = date('Y-m-d H:i:s'); // Set published_at to current time
        }

        if ($id == '') {
            // Insert new post
            $sql = "INSERT INTO posts (title, slug, content, author_id, editor_id, status, thumbnail_path, banner_path, published_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path, $published_at];
            $types = "sssssssss"; // 9 parameters
        } else {
            // Update existing post
            if ($status !== 'published') {
                $published_at = null; // Clear published_at if status is not 'published'
            }
            $sql = "UPDATE posts 
                SET title = ?, slug = ?, content = ?, author_id = ?, editor_id = ?, status = ?, thumbnail_path = ?, banner_path = ?, published_at = ? 
                WHERE post_id = ?";
            $params = [$title, $slug, $content, $author_id, $editor_id, $status, $thumbnail_path, $banner_path, $published_at, $id];
            $types = "sssssssssi"; // 10 parameters
        }

        $success = DBHelper::executeQuery($sql, $params, $types);

        if (!$success) {
            throw new \Exception("Error: Unable to save or update post.");
        }

        if ($id == '') {
            $id = DBHelper::fetchSingleValue("SELECT post_id FROM posts ORDER BY created_at DESC LIMIT 1; ", [], "");
        }

        return $id;
    }

    /**
     * Send status change notification emails to author and editor.
     */
    public static function sendStatusChangeEmail($author_id, $editor_id, $title, $post_id, $oldStatus, $newStatus, $reason): void
    {
        // Send email notification to the author
        $author = Post::getUserById($author_id);
        $authorEmail = $author['email'] ?? $_ENV['DEFAULT_FROM_EMAIL'];

        Common::sendEmail(
            $authorEmail,
            'Post Status Changed',
            "The status of your post titled '$title' (ID: $post_id) has changed from '$oldStatus' to '$newStatus'.\n\nReason: $reason"
        );

        // Send email notification to the editor
        if ($editor_id) {
            $editor = Post::getUserById($editor_id);
            $editorEmail = $editor['email'] ?? $_ENV['DEFAULT_FROM_EMAIL'];

            Common::sendEmail(
                $editorEmail,
                'Post Status Changed',
                "The status of the post titled '$title' (ID: $post_id) that you are editing has changed from '$oldStatus' to '$newStatus'.\n\nReason: $reason"
            );
        }
    }

    /**
     * Handle the saving of post categories.
     */
    public static function handlePostCategories($post_id, $categories): void
    {
        $sqlDelete = "DELETE FROM post_categories WHERE post_id = ?";
        DBHelper::executeQuery($sqlDelete, [$post_id], "s");

        $sqlInsert = "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)";
        foreach ($categories as $category_id) {
            DBHelper::executeQuery($sqlInsert, [$post_id, $category_id], "ss");
        }
    }

    /**
     * Handle the saving of post tags.
     */
    public static function handlePostTags($post_id, $tags): void
    {
        $sqlDelete = "DELETE FROM post_tags WHERE post_id = ?";
        DBHelper::executeQuery($sqlDelete, [$post_id], "s");

        $sqlInsert = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
        foreach ($tags as $tag_id) {
            DBHelper::executeQuery($sqlInsert, [$post_id, $tag_id], "ss");
        }
    }
}
