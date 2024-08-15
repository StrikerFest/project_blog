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
}
