<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', ['title' => isset($args['category']['category_id']) ? 'Edit category' : 'Create category']);
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
<div class="container">
    <?php if (isset($args['category']['category_id'])) : ?>
        <h1>Update Category ID: <?= $args['category']['category_id'] ?></h1>
    <?php else : ?>
        <h1>Create New Category</h1>
    <?php endif; ?>
    <form id="createCategoryForm" method="POST" class="form">
        <input type="hidden" value="<?= $args['category']['category_id'] ?? '' ?>" name="id"/>

        <div>Name</div>
        <input type="text" placeholder="Name" value="<?= $args['category']['name'] ?? '' ?>" class="form__input" id="name" name="name" required />

        <div>Slug</div>
        <input type="text" placeholder="Slug" value="<?= $args['category']['slug'] ?? '' ?>" class="form__input" id="slug" name="slug" required />

        <div>Status</div>
        <div class="select">
            <select name="status" id="status" required>
                <option value="enabled" <?= (!empty($args['category']) && $args['category']['status'] == 'enabled') ? 'selected' : ''  ?>>Enable</option>
                <option value="disabled" <?= (!empty($args['category']) && $args['category']['status'] == 'disabled') ? 'selected' : ''  ?>>Disabled</option>
            </select>
        </div>

        <div>Description</div>
        <textarea type="text" placeholder="Description" id="description" name="description" required><?= $args['category']['description'] ?? '' ?></textarea>

        <div>Position</div>
        <input type="number" placeholder="Position" value="<?= $args['category']['position'] ?? '0' ?>" class="form__input" id="position" name="position" required />

        <button type="submit" class="btn">Save Category</button>
    </form>
</div>
</body>
