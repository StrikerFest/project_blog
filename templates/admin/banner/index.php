<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\models\Banner;

$banners = $args['banners'];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Banner Management'
]);
?>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($banners as $banner): ?>
            <tr>
                <td><?php echo htmlspecialchars($banner['id']); ?></td>
                <td><?php echo htmlspecialchars($banner['title']); ?></td>
                <td><?php echo htmlspecialchars(Banner::getBannerTypeById($banner['type_id'])); ?></td>
                <td><?php echo htmlspecialchars($banner['start_date']); ?></td>
                <td><?php echo htmlspecialchars($banner['end_date']); ?></td>
                <td><?php echo $banner['deleted_at'] ? 'Deleted' : ($banner['is_active'] ? 'Active' : 'Inactive'); ?></td>
                <td>
                    <?php if ($banner['deleted_at']): ?>
                        <a href="banner/delete?action=recover&id=<?= $banner['id']; ?>" class="btn btn-recover">Recover</a>
                    <?php else: ?>
                        <a href="banner/edit?id=<?= $banner['id']; ?>" class="btn">Edit</a>
                        <a href="banner/delete?action=delete&id=<?= $banner['id']; ?>" class="btn btn-delete">Delete</a>
                    <?php endif; ?>
                </td>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const recoverButtons = document.querySelectorAll('.btn-recover');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmDelete = confirm('Are you sure you want to delete this banner?');
                if (confirmDelete) {
                    window.location.href = this.href;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmRecover = confirm('Are you sure you want to recover this banner?');
                if confirmRecover {
                    window.location.href = this.href;
                }
            });
        });
    });
</script>
