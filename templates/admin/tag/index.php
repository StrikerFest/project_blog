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
        <h1>Tag</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($args['tags'] as $tag) : ?>
                    <tr class="table-row">
                        <td><?= $tag['id']; ?></td>
                        <td><?= $tag['name']; ?></td>
                        <td><?= $tag['status']; ?></td>
                        <td>
                            <a href="tag/edit?id=<?= $tag['id']; ?>" class="btn_action">Edit</a>
                            <a href="tag/delete?id=<?= $tag['id']; ?>" class="btn_action">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>