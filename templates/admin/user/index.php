<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

$users = $args['users'];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Quản lý người dùng',
    'permission' => 'ADMIN_ONLY',
]);
?>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
            <th style="display:none;">Ngày cập nhật</th> <!-- Cột Ngày cập nhật ẩn -->
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
                <td><?php echo $user['deleted_at'] ? 'Không hoạt động' : 'Hoạt động'; ?></td>
                <td>
                    <?php if ($user['deleted_at']): ?>
                        <a href="#" class="btn recover-user" data-user-id="<?= $user['user_id']; ?>">Khôi phục</a>
                    <?php else: ?>
                        <a href="user/edit?id=<?= $user['user_id']; ?>" class="btn">Chỉnh sửa</a>
                        <a href="#" class="btn delete-user" data-user-id="<?= $user['user_id']; ?>">Xóa</a>
                    <?php endif; ?>
                </td>
                <td style="display:none;"><?php echo htmlspecialchars($user['updated_at']); ?></td> <!-- Dữ liệu Ngày cập nhật ẩn -->
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
            "order": [[7, "desc"]], // Sắp xếp theo cột thứ 8 (ngày cập nhật) theo thứ tự giảm dần
            "columnDefs": [
                { "targets": 7, "visible": false } // Ẩn cột ngày cập nhật
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
                const confirmDelete = confirm('Bạn có chắc chắn muốn xóa người dùng này không?');

                if (confirmDelete) {
                    window.location.href = `/admin/user/delete?id=${userId}&action=delete`;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const confirmRecover = confirm('Bạn có chắc chắn muốn khôi phục người dùng này không?');

                if (confirmRecover) {
                    window.location.href = `/admin/user/delete?id=${userId}&action=recover`;
                }
            });
        });
    });
</script>
