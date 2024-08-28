<?php

use inc\helpers\admin\Post;
use inc\helpers\Common;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', ['title' => 'burogu', 'permission' => 'NO_EDITOR_CREATE']);

$current_user = Common::getCurrentBackendUser();
$post = $args['post'];

$post_category_ids = Post::getPostCategories($post['post_id'] ?? null, 'id', 0);
$post_tag_ids = Post::getPostTags($post['post_id'] ?? null, 'id');

$allowed = Post::canChangeStatus($post['status'] ?? null, $current_user['role']);
$permissionMissing = !$allowed ? "Bạn không có quyền thay đổi trạng thái bài viết." : '';

?>

<body>
<div class="edit-container">
    <?php if (isset($post['post_id'])): ?>
        <h1 class="edit-title">Cập nhật Bài viết ID: <?= $post['post_id'] ?></h1>
    <?php else: ?>
        <h1 class="edit-title">Tạo Bài viết Mới</h1>
    <?php endif; ?>
    <form id="post-edit-createPostForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="<?= $post['post_id'] ?? '' ?>" name="id"/>
        <input type="hidden" value="<?= $current_user['id'] ?? $args['current_user_id'] ?>" name="author_id"/>
        <input type="hidden" value="<?= $post['approved_by'] ?? '' ?>" name="approved_by"/>

        <div class="edit-field">
            <label for="post-edit-title">Tiêu đề:</label>
            <input type="text" id="post-edit-title" name="title" value="<?= $post['title'] ?? '' ?>" placeholder="Tiêu đề" maxlength="255" required>
        </div>

        <div class="edit-field">
            <label for="post-edit-content">Nội dung:</label>
            <textarea id="post-edit-content" name="content" placeholder="Nội dung"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
        </div>

        <!-- Thumbnail Upload Field -->
        <div class="edit-field">
            <label for="post-edit-thumbnail">Hình thu nhỏ:</label>
            <?php if (!empty($post['thumbnail_path'])): ?>
                <div class="image-preview">
                    <p>Hình thu nhỏ hiện tại:</p>
                    <img src="<?= $post['thumbnail_path']; ?>" alt="Hình thu nhỏ hiện tại" class="existing-image">
                </div>
            <?php endif; ?>
            <input type="file" id="post-edit-thumbnail" name="thumbnail" accept="image/*">
            <div id="thumbnail-preview" class="image-preview"></div>
        </div>

        <!-- Banner Upload Field -->
        <div class="edit-field">
            <label for="post-edit-banner">Banner:</label>
            <?php if (!empty($post['banner_path'])): ?>
                <div class="image-preview">
                    <p>Banner hiện tại:</p>
                    <img src="<?= $post['banner_path']; ?>" alt="Banner hiện tại" class="existing-image">
                </div>
            <?php endif; ?>
            <input type="file" id="post-edit-banner" name="banner" accept="image/*">
            <div id="banner-preview" class="image-preview"></div>
        </div>

        <div class="edit-field">
            <label>Danh mục:</label>
            <select name="categories[]" id="post-edit-categories" multiple required>
                <?php foreach ($args['categories'] as $category): ?>
                    <option value="<?= $category['category_id'] ?>"
                        <?= (in_array($category['category_id'], $post_category_ids ?? [])) ? 'selected' : '' ?>>
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="edit-field">
            <label>Thẻ:</label>
            <select name="tags[]" id="post-edit-tags" multiple required>
                <?php foreach ($args['tags'] as $tag): ?>
                    <option value="<?= $tag['tag_id'] ?>"
                        <?= (in_array($tag['tag_id'], $post_tag_ids ?? [])) ? 'selected' : '' ?>>
                        <?= $tag['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="edit-field">
            <label for="post-edit-status">Trạng thái: <?= $permissionMissing ?></label>
            <select id="post-edit-status" name="status" required <?= !$allowed ? 'disabled' : '' ?>>
                <?php
                $currentStatus = $post['status'] ?? null;

                $availableStatuses = [];
                switch ($current_user['role']) {
                    case 'author':
                        if (!$currentStatus) {
                            $availableStatuses = ['draft', 'pending_approval'];
                        } else {
                            switch ($currentStatus) {
                                case 'draft':
                                case 'approval_retracted':
                                case 'approval_denied':
                                    $availableStatuses = ['pending_approval'];
                                    break;
                            }
                        }
                        break;
                    case 'editor':
                        if (!$currentStatus) {
                            $availableStatuses = ['draft']; // Editors cannot create posts
                        } else {
                            switch ($currentStatus) {
                                case 'pending_approval':
                                    $availableStatuses = ['approval_denied', 'approved'];
                                    break;
                                case 'draft':
                                case 'approval_retracted':
                                    $availableStatuses = ['pending_approval'];
                                    break;
                                case 'approved':
                                case 'published_retracted':
                                    $availableStatuses = ['published', 'approval_retracted'];
                                    break;
                            }
                        }
                        break;
                    case 'admin':
                        if (!$currentStatus) {
                            $availableStatuses = ['draft'];
                        } else {
                            $availableStatuses[] = $currentStatus;
                            switch ($currentStatus) {
                                case 'draft':
                                    $availableStatuses[] = 'pending_approval';
                                    break;
                                case 'pending_approval':
                                    $availableStatuses[] = 'approval_denied';
                                    $availableStatuses[] = 'approved';
                                    break;
                                case 'approval_denied':
                                case 'approval_retracted':
                                    break;
                                case 'approved':
                                    $availableStatuses[] = 'published';
                                    break;
                                case 'published_retracted':
                                    $availableStatuses[] = 'published';
                                    $availableStatuses[] = 'approval_retracted';
                                    break;
                                case 'published':
                                    $availableStatuses[] = 'published_retracted';
                                    break;
                            }
                        }
                        break;
                    default:
                        echo '<option value="">Vai trò không hợp lệ</option>';
                        break;
                }

                echo "<option value='$currentStatus' selected>$currentStatus</option>";

                foreach ($availableStatuses as $status) {
                    if ($currentStatus === $status) {
                        continue;
                    }
                    echo "<option value='$status'>$status</option>";
                }
                ?>
            </select>
        </div>
        <div class="edit-field" id="reason-field" style="display: none;">
            <label for="post-edit-reason">Lý do thay đổi trạng thái:</label>
            <textarea id="post-edit-reason" name="reason" placeholder="Cung cấp lý do cho sự thay đổi trạng thái"></textarea>
        </div>
        <div class="edit-field" id="editor-field" style="display: none;">
            <label for="post-edit-editor">Chọn Biên tập viên:</label>
            <select id="post-edit-editor" name="editor_id">
                <?php foreach ($args['editors'] as $editor): ?>
                    <option value="<?= $editor['user_id'] ?>" <?= ($post['editor_id'] ?? null) == $editor['user_id'] ? 'selected' : '' ?>>
                        <?= $editor['username'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="edit-field">
            <button type="submit" class="edit-btn">Lưu Bài viết</button>
        </div>
    </form>
</div>

<script src="<?= Common::getAssetPath('js/admin/post/form-display.js') ?>"></script>
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    }
</script>
<script type="module" src="<?= Common::getAssetPath('js/admin/post/editor.js') ?>"></script>
<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>
