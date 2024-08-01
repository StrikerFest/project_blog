<?php

namespace inc\models;

use Exception;
use inc\helpers\DB;

class Like
{
    private $db;

    public function __construct()
    {
        $this->db = (new DB())->db_connect();
    }

    public function checkLike($userId, $postId): bool|array|null
    {
        $sql = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * @throws Exception
     */
    public function addLike($userId, $postId): void
    {
        $this->db->begin_transaction();
        try {
            $sql = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $userId, $postId);
            $stmt->execute();

            $updateLikesSql = "UPDATE posts SET likes = likes + 1 WHERE post_id = ?";
            $updateLikesStmt = $this->db->prepare($updateLikesSql);
            $updateLikesStmt->bind_param('i', $postId);
            $updateLikesStmt->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function removeLike($userId, $postId): void
    {
        $this->db->begin_transaction();
        try {
            $sql = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $userId, $postId);
            $stmt->execute();

            $updateLikesSql = "UPDATE posts SET likes = likes - 1 WHERE post_id = ?";
            $updateLikesStmt = $this->db->prepare($updateLikesSql);
            $updateLikesStmt->bind_param('i', $postId);
            $updateLikesStmt->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
