<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

$users = $args['users'];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'User Management',
    'permission' => 'ADMIN_ONLY',
]);
?>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Action</th>
            <th style="display:none;">Updated At</th> <!-- Hidden Updated At column -->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                <td><?php echo $user['deleted_at'] ? 'Inactive' : 'Active'; ?></td>
                <td>
                    <?php if ($user['deleted_at']): ?>
                        <a href="#" class="btn recover-user" data-user-id="<?= $user['user_id']; ?>">Recover</a>
                    <?php else: ?>
                        <a href="user/edit?id=<?= $user['user_id']; ?>" class="btn">Edit</a>
                        <a href="#" class="btn delete-user" data-user-id="<?= $user['user_id']; ?>">Delete</a>
                    <?php endif; ?>
                </td>
                <td style="display:none;"><?php echo htmlspecialchars($user['updated_at']); ?></td> <!-- Hidden Updated At column data -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
Common::requireTemplate('admin/layouts/footer.php');
?>
<script>
    $(document).ready(function () {
        $('#listing-table').DataTable({
            "searching": true,
            "order": [[7, "desc"]], // Sort by the 8th column (updated_at) in descending order
            "columnDefs": [
                { "targets": 7, "visible": false } // Hide the updated_at column
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-user');
        const recoverButtons = document.querySelectorAll('.recover-user');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const confirmDelete = confirm('Are you sure you want to delete this user?');

                if (confirmDelete) {
                    window.location.href = `/admin/user/delete?id=${userId}&action=delete`;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const confirmRecover = confirm('Are you sure you want to recover this user?');

                if (confirmRecover) {
                    window.location.href = `/admin/user/delete?id=${userId}&action=recover`;
                }
            });
        });
    });
</script>
