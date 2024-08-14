<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

$banner = $args['banner'];
$bannerTypes = $args['banner_types'];

Common::requireTemplate('admin/layouts/headers.php', ['title' => isset($banner['id']) ? 'Edit Banner' : 'Create Banner']);
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
<div class="container">
    <?php if (isset($banner['id'])) : ?>
        <h1>Update Banner ID: <?= $banner['id'] ?></h1>
    <?php else : ?>
        <h1>Create New Banner</h1>
    <?php endif; ?>
    <form id="createBannerForm" method="POST" enctype="multipart/form-data" class="form">
        <input type="hidden" value="<?= $banner['id'] ?? '' ?>" name="id"/>
        <input type="hidden" value="<?= $banner['image_path'] ?? '' ?>" name="existing_image_path"/>

        <div>Title</div>
        <input type="text" placeholder="Title" value="<?= $banner['title'] ?? '' ?>" class="form__input" id="title" name="title" required />

        <div>Image</div>
        <input type="file" class="form__input" id="image" name="image">
        <?php if (!empty($banner['image_path'])): ?>
            <img src="<?= htmlspecialchars($banner['image_path']) ?>" alt="Banner Image" style="margin-top: 10px; max-width: 200px;">
        <?php endif; ?>

        <div>Text</div>
        <textarea placeholder="Text" id="text" name="text" class="form__input"><?= $banner['text'] ?? '' ?></textarea>

        <div>Link</div>
        <input type="text" placeholder="Link" value="<?= $banner['link'] ?? '' ?>" class="form__input" id="link" name="link" />

        <div>Start Date</div>
        <input type="datetime-local" value="<?= $banner['start_date'] ?? '' ?>" class="form__input" id="start_date" name="start_date" required />

        <div>End Date</div>
        <input type="datetime-local" value="<?= $banner['end_date'] ?? '' ?>" class="form__input" id="end_date" name="end_date" required />

        <div>Status</div>
        <div class="select">
            <label>
                <input type="checkbox" name="is_active" <?= isset($banner['is_active']) && $banner['is_active'] ? 'checked' : '' ?>> Active
            </label>
        </div>

        <div>Banner Type</div>
        <div class="select">
            <select name="type_id" id="type_id" required>
                <?php foreach ($bannerTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= (isset($banner['type_id']) && $banner['type_id'] == $type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Save Banner</button>
    </form>
</div>
</body>
