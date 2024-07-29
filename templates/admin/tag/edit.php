<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', ['title' => isset($args['tag']['id']) ? 'Edit tag' : 'Create tag']);
?>

<body>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
    <div class="container">
        <?php if (isset($args['tag']['tag_id'])) : ?>
            <h1>Update Tag ID: <?= $args['tag']['tag_id'] ?></h1>
        <?php else : ?>
            <h1>Create New Tag</h1>
        <?php endif; ?>
        <form id="createTagForm" method="POST" class="form">
            <input type="hidden" value="<?= $args['tag']['tag_id'] ?? '' ?>" name="id" />

            <div>Name</div>
            <input type="text" placeholder="Name" value="<?= $args['tag']['name'] ?? '' ?>" class="form__input" name="name" required />

            <div>Status</div>
            <div class="select">
                <select name="status" id="status" required>
                    <option value="enabled" <?= (!empty($args['tag']) && $args['tag']['status'] == 'enabled') ? 'selected' : ''  ?>>Enable</option>
                    <option value="disabled" <?= (!empty($args['tag']) && $args['tag']['status'] == 'disabled') ? 'selected' : ''  ?>>Disabled</option>
                </select>
            </div>

            <div>Position</div>
            <input type="number" placeholder="Position" value="<?= $args['tag']['position'] ?? '0' ?>" class="form__input" id="position" name="position" required />

            <button type="submit" class="btn">Save Tag</button>
        </form>
    </div>
</body>