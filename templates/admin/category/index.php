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

<body>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
    <div class="container">
        <h1>Categories</h1>
        <div>
            <table class="styled-table">
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
                            <td><?= $category['id']; ?></td>
                            <td><?= $category['name']; ?></td>
                            <td><?= $category['status']; ?></td>
                            <td><?= $category['description']; ?></td>
                            <td>
                                <a href="category/edit?id=<?= $category['id']; ?>" class="btn_action">Edit</a>
                                <a href="category/delete?id=<?= $category['id']; ?>" class="btn_action">Delete</a>
                            </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>