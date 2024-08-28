<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

$approvalLogs = $args['approvalLogs'];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Approval Logs'
]);
?>
<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id phê duyệt</th>
            <th>Id bài viết</th>
            <th>Tiêu đề bài viết</th>
            <th>Người dùng</th>
            <th>Trạng thái ban đầu</th>
            <th>Trạng thái được chuyển</th>
            <th>Lý do</th>
            <th>Thời gian</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($approvalLogs as $log): ?>
            <tr>
                <td><?php echo htmlspecialchars($log['approval_id']); ?></td>
                <td><?php echo htmlspecialchars($log['post_id']); ?></td>
                <td><?php echo htmlspecialchars($log['post_title']); ?></td>
                <td><?php echo htmlspecialchars($log['user_name']); ?></td>
                <td><?php echo htmlspecialchars($log['status_from']); ?></td>
                <td><?php echo htmlspecialchars($log['status_to']); ?></td>
                <td><?php echo htmlspecialchars($log['reason'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($log['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
Common::requireTemplate('admin/layouts/footer.php');
?>
<script>
    $(document).ready(function() {
        $('#listing-table').DataTable({
            "order": [[0, "desc"]],
            "searching": true
        });
    });
</script>
