<?php
/**
 * @var mixed $args
 */

use inc\helpers\Common;

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Category'
]);
?>
<div class="listing-container">
    <h1>Categories</h1>
    <div>
        <table id="categoryTable" class="listing-styled-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Status</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($args['categories'] as $category) : ?>
                <tr class="table-row <?= $category['deleted_at'] ? 'deleted-row' : ''; ?>">
                    <td class="text-align-center"><?= $category['category_id']; ?></td>
                    <td><?= $category['name']; ?></td>
                    <td class="text-align-center"><?= $category['slug']; ?></td>
                    <td class="text-align-center"><?= $category['description']; ?></td>
                    <td class="text-align-center"><?= $category['status']; ?></td>
                    <td class="text-align-center"><?= $category['position']; ?></td>
                    <td class="text-align-center">
                        <?php if ($category['deleted_at']) : ?>
                            <a href="category/delete?action=recover&id=<?= $category['category_id']; ?>" class="listing-btn_action">Recover</a>
                        <?php else : ?>
                            <a href="category/edit?id=<?= $category['category_id']; ?>" class="listing-btn_action">Edit</a>
                            <a href="category/delete?action=delete+&id=<?= $category['category_id']; ?>" class="listing-btn_action">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable();
    });
</script>
