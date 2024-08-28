<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

$banner = $args['banner'];
$bannerTypes = $args['banner_types'];

Common::requireTemplate('admin/layouts/headers.php', ['title' => isset($banner['id']) ? 'Chỉnh sửa Banner' : 'Tạo Banner']);
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.scss') ?>">
<div class="container">
    <?php if (isset($banner['id'])) : ?>
        <h1>Cập nhật banner Id: <?= $banner['id'] ?></h1>
    <?php else : ?>
        <h1>Tạo banner mới</h1>
    <?php endif; ?>
    <form id="createBannerForm" method="POST" enctype="multipart/form-data" class="form">
        <input type="hidden" value="<?= $banner['id'] ?? '' ?>" name="id"/>
        <input type="hidden" value="<?= $banner['image_path'] ?? '' ?>" name="existing_image_path"/>

        <div>Tiêu đề</div>
        <input type="text" placeholder="Tiêu đề" value="<?= $banner['title'] ?? '' ?>" class="form__input" id="title" name="title" required />

        <div>Hình ảnh</div>
        <input type="file" class="form__input" id="image" name="image">
        <?php if (!empty($banner['image_path'])): ?>
            <img src="<?= htmlspecialchars($banner['image_path']) ?>" alt="Hình banner" style="margin-top: 10px; max-width: 200px;">
        <?php endif; ?>

        <div>Văn bản</div>
        <textarea placeholder="Văn bản" id="text" name="text" class="form__input"><?= $banner['text'] ?? '' ?></textarea>

        <div>Liên kết</div>
        <input type="text" placeholder="Liên kết" value="<?= $banner['link'] ?? '' ?>" class="form__input" id="link" name="link" />

        <div>Ngày bắt đầu</div>
        <input type="datetime-local" value="<?= $banner['start_date'] ?? '' ?>" class="form__input" id="start_date" name="start_date" required />

        <div>Ngày kết thúc</div>
        <input type="datetime-local" value="<?= $banner['end_date'] ?? '' ?>" class="form__input" id="end_date" name="end_date" required />

        <div>Trạng thái</div>
        <div class="select">
            <label>
                <input type="checkbox" name="is_active" <?= isset($banner['is_active']) && $banner['is_active'] ? 'checked' : '' ?>> Kích hoạt
            </label>
        </div>

        <div>Loại banner</div>
        <div class="select">
            <select name="type_id" id="type_id" required>
                <?php foreach ($bannerTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= (isset($banner['type_id']) && $banner['type_id'] == $type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Lưu banner</button>
    </form>
</div>
</body>
