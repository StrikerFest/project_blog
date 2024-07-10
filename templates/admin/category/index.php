<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Category'
]);
?>
<div class="listing-container">
    <h1>Categories</h1>
    <div>
        <table class="listing-styled-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Status</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($args['categories'] as $category) : ?>
                <tr class="table-row">
                    <td class="text-align-center"><?= $category['id']; ?></td>
                    <td><?= $category['name']; ?></td>
                    <td class="text-align-center"><?= $category['status']; ?></td>
                    <td class="text-align-center"><?= $category['description']; ?></td>
                    <td class="text-align-center">
                        <a href="category/edit?id=<?= $category['id']; ?>" class="listing-btn_action">Edit</a>
                        <a href="category/delete?id=<?= $category['id']; ?>" class="listing-btn_action">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
