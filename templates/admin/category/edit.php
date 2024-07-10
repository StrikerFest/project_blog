<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', ['title' => isset($args['category']['id']) ? 'Edit category' : 'Create category']);
?>

<body>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
    <div class="container">
        <?php if (isset($args['category']['id'])) : ?>
            <h1>Update Category ID: <?= $args['category']['id'] ?></h1>
        <?php else : ?>
            <h1>Create New Category</h1>
        <?php endif; ?>
        <form id="createCategoryForm" method="POST" class="form">
            <input type="hidden" value="<?= $args['category']['id'] ?? '' ?>" name="id" />
            <div>Name</div>
            <input type="text" placeholder="Name" value="<?= $args['category']['name'] ?? '' ?>" class="form__input" id="name" name="name" required />

            <div>Status</div>
            <div class="select">
                <select name="status" id="status" required>
                    <option value="enabled" <?= (!empty($args['category']) && $args['category']['status'] == 'enabled') ? 'selected' : ''  ?>>Enable</option>
                    <option value="disabled" <?= (!empty($args['category']) && $args['category']['status'] == 'disabled') ? 'selected' : ''  ?>>Disabled</option>
                </select>
            </div>
            <div>Description</div>
            <textarea type="text" placeholder="Description" id="description" name="description" required><?= $args['category']['description'] ?? '' ?></textarea>
            <button type="submit" class="btn">Save Category</button>
        </form>
    </div>
</body>