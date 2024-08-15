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
            <th>Approval ID</th>
            <th>Post ID</th>
            <th>Post Title</th>
            <th>User</th>
            <th>Status From</th>
            <th>Status To</th>
            <th>Reason</th>
            <th>Date</th>
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
            "searching": true
        });
    });
</script>
