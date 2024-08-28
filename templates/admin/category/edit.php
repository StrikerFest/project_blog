<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => isset($args['category']['category_id']) ? 'Chỉnh sửa danh mục' : 'Tạo danh mục', 'permission' => 'NO_EDITOR_CREATE'
]);

$category = $args['categoryData'] ?? $args['category'];
$errors = $args['errors'] ?? [];
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
<div class="container">
    <?php if (isset($category['category_id'])) : ?>
        <h1>Cập nhật danh mục ID: <?= $category['category_id'] ?></h1>
    <?php else : ?>
        <h1>Tạo danh mục mới</h1>
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

    <form id="createCategoryForm" method="POST" class="form">
        <input type="hidden" value="<?= htmlspecialchars($category['category_id'] ?? '') ?>" name="id"/>

        <div>Tên</div>
        <input type="text" placeholder="Tên" value="<?= htmlspecialchars($category['name'] ?? '') ?>" class="form__input" id="name" name="name" required/>

        <div>Slug</div>
        <input type="text" placeholder="Slug" value="<?= htmlspecialchars($category['slug'] ?? '') ?>" class="form__input" id="slug" name="slug" required/>

        <div>Trạng thái</div>
        <div class="select">
            <select name="status" id="status" required>
                <option value="enabled" <?= (!empty($category) && $category['status'] == 'enabled') ? 'selected' : '' ?>>Kích hoạt</option>
                <option value="disabled" <?= (!empty($category) && $category['status'] == 'disabled') ? 'selected' : '' ?>>Không kích hoạt</option>
            </select>
        </div>

        <div>Mô tả</div>
        <textarea type="text" placeholder="Mô tả" id="description" name="description" required><?= htmlspecialchars($category['description'] ?? '') ?></textarea>

        <div>Vị trí</div>
        <input type="number" placeholder="Vị trí" value="<?= htmlspecialchars($category['position'] ?? '0') ?>" class="form__input" id="position" name="position" required/>

        <button type="submit" class="btn">Lưu danh mục</button>
    </form>
</div>
</body>
