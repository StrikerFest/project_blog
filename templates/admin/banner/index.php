<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\models\Banner;

$banners = $args['banners'];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Quản lý Banner'
]);
?>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Tiêu đề</th>
            <th>Loại</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
            <th style="display:none;">Cập nhật vào</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($banners as $banner): ?>
            <tr>
                <td><?php echo htmlspecialchars($banner['id']); ?></td>
                <td><?php echo htmlspecialchars($banner['title']); ?></td>
                <td><?php echo htmlspecialchars(Banner::getBannerTypeById($banner['type_id'], false)); ?></td>
                <td><?php echo htmlspecialchars($banner['start_date']); ?></td>
                <td><?php echo htmlspecialchars($banner['end_date']); ?></td>
                <td><?php echo $banner['deleted_at'] ? 'Đã xóa' : ($banner['is_active'] ? 'Kích hoạt' : 'Không kích hoạt'); ?></td>
                <td>
                    <?php if ($banner['deleted_at']): ?>
                        <a href="banner/delete?action=recover&id=<?= $banner['id']; ?>" class="btn btn-recover">Khôi phục</a>
                    <?php else: ?>
                        <a href="banner/edit?id=<?= $banner['id']; ?>" class="btn">Sửa</a>
                        <a href="banner/delete?action=delete&id=<?= $banner['id']; ?>" class="btn btn-delete">Xóa</a>
                    <?php endif; ?>
                </td>
                <td style="display:none;"><?php echo htmlspecialchars($banner['updated_at']); ?></td> <!-- Hidden Updated At column data -->
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
            "searching": true,
            "order": [[7, "desc"]],
            "columnDefs": [
                { "targets": 7, "visible": false }
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const recoverButtons = document.querySelectorAll('.btn-recover');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmDelete = confirm('Bạn có chắc chắn muốn xóa banner này không?');
                if (confirmDelete) {
                    window.location.href = this.href;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmRecover = confirm('Bạn có chắc chắn muốn khôi phục banner này không?');
                if (confirmRecover) {
                    window.location.href = this.href;
                }
            });
        });
    });
</script>
