<?php

namespace inc\models;

use inc\helpers\DB;

class ApprovalLog
{
    private $db;

    public function __construct()
    {
        $this->db = (new DB())->db_connect();
    }

    public function createLog($post_id, $user_id, $status_from, $status_to, $reason)
    {
        $sql = "INSERT INTO approval_logs (post_id, user_id, status_from, status_to, reason) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisss", $post_id, $user_id, $status_from, $status_to, $reason);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAllApprovalLogs(): array
    {
        $conn = DB::db_connect();
        $sql = "
            SELECT al.*, p.post_id, p.title AS post_title, u.username AS user_name
            FROM approval_logs al
            INNER JOIN posts p ON al.post_id = p.post_id
            INNER JOIN users u ON al.user_id = u.user_id
            ORDER BY al.created_at DESC
        ";
        $result = $conn->query($sql);
        $approvalLogs = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $approvalLogs[] = $row;
            }
        }

        $conn->close();
        return $approvalLogs;
    }
}
