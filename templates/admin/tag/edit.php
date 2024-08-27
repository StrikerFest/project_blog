<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => isset($args['tag']['tag_id']) ? 'Edit Tag' : 'Create Tag', 'permission' => 'NO_EDITOR_CREATE'
]);

$tag = $args['tagData'] ?? $args['tag'];
$errors = $args['errors'] ?? [];
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
<div class="container">
    <?php if (isset($tag['tag_id'])) : ?>
        <h1>Update Tag ID: <?= $tag['tag_id'] ?></h1>
    <?php else : ?>
        <h1>Create New Tag</h1>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="createTagForm" method="POST" class="form">
        <input type="hidden" value="<?= htmlspecialchars($tag['tag_id'] ?? '') ?>" name="id"/>

        <div>Name</div>
        <input type="text" placeholder="Name" value="<?= htmlspecialchars($tag['name'] ?? '') ?>" class="form__input" id="name" name="name" required />

        <div>Slug</div>
        <input type="text" placeholder="Slug" value="<?= htmlspecialchars($tag['slug'] ?? '') ?>" class="form__input" id="slug" name="slug" required />

        <div>Status</div>
        <div class="select">
            <select name="status" id="status" required>
                <option value="enabled" <?= (!empty($tag) && $tag['status'] == 'enabled') ? 'selected' : ''  ?>>Enable</option>
                <option value="disabled" <?= (!empty($tag) && $tag['status'] == 'disabled') ? 'selected' : ''  ?>>Disabled</option>
            </select>
        </div>

        <div>Position</div>
        <input type="number" placeholder="Position" value="<?= htmlspecialchars($tag['position'] ?? '0') ?>" class="form__input" id="position" name="position" required />

        <button type="submit" class="btn">Save Tag</button>
    </form>
</div>
</body>
