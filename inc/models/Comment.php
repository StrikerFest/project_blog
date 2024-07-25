<?php

namespace inc\models;

use inc\helpers\DB;

class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = (new DB())->db_connect();
    }

    public function saveComment($post_id, $user_id, $content): int
    {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $content);

        if ($stmt->execute()) {
            $comment_id = $stmt->insert_id;
            $stmt->close();
            return $comment_id;
        } else {
            $stmt->close();
            return 0; 
        }
    }

    public function getCommentsByPostId($post_id)
    {
        $stmt = $this->db->prepare("SELECT users.user_id, users.username, users.profile_image, comments.comment_id, comments.content, comments.created_at 
                                    FROM comments 
                                    JOIN users ON comments.user_id = users.user_id 
                                    WHERE comments.post_id = ? 
                                    ORDER BY comments.created_at DESC");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $comments;
    }

    public function deleteComment($comment_id, $post_id, $user_id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE comment_id = ? AND post_id = ? AND user_id = ?");
        $stmt->bind_param("iii", $comment_id, $post_id, $user_id);

        return $stmt->execute();
    }
}
