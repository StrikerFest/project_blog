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
                <th style="display:none;">Updated At</th> <!-- Hidden Updated At column -->
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
                            <a href="category/delete?action=delete&id=<?= $category['category_id']; ?>" class="listing-btn_action">Delete</a>
                        <?php endif; ?>
                    </td>
                    <td style="display:none;"><?= $category['updated_at']; ?></td> <!-- Hidden Updated At column data -->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            "order": [[7, "desc"]], // Sort by the 8th column (updated_at) in descending order
            "columnDefs": [
                { "targets": 7, "visible": false } // Hide the updated_at column
            ]
        });
    });
</script>
