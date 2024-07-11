<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', ['title' => 'burogu']);

// Test data
$current_user = $_SESSION['user_backend'] ?? null;
// TODO: LOGOUT
if ($current_user === null) {
    exit;
}

$args['categories'] = [];
$args['tags'] = [];
$args['post']['status'] = [];
?>

<body>
<div class="post-edit-container">
    <?php if(isset($args['post']['id'])): ?>
        <h1 class="post-edit-title">Update Post ID: <?= $args['post']['id'] ?></h1>
    <?php else: ?>
        <h1 class="post-edit-title">Create New Post</h1>
    <?php endif; ?>
    <form id="post-edit-createPostForm" method="POST">
        <input type="hidden" value="<?= $args['post']['id'] ?? '' ?>" name="id" />
        <input type="hidden" value="<?= $current_user['id'] ?? $args['current_user_id'] ?>" name="author_id" />
        <input type="hidden" value="<?= $args['post']['approved_by'] ?? '' ?>" name="approved_by" />

        <div class="post-edit-field">
            <label for="post-edit-title">Title:</label>
            <input type="text" id="post-edit-title" name="title" value="<?= $args['post']['title'] ?? '' ?>" placeholder="Title" maxlength="255" required>
        </div>

        <div class="post-edit-field">
            <label for="post-edit-content">Content:</label>
            <textarea id="post-edit-content" name="content" placeholder="Content" required><?= $args['post']['content'] ?? '' ?></textarea>
        </div>

        <div class="post-edit-field">
            <label>Categories:</label>
            <?php foreach($args['categories'] as $category): ?>
                <input type="checkbox" id="post-edit-category<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>"
                    <?php if(in_array($category['id'], $args['post']['categories'] ?? [])) echo 'checked'; ?>>
                <label for="post-edit-category<?= $category['id'] ?>"><?= $category['name'] ?></label>
            <?php endforeach; ?>
        </div>

        <div class="post-edit-field">
            <label>Tags:</label>
            <?php foreach($args['tags'] as $tag): ?>
                <input type="checkbox" id="post-edit-tag<?= $tag['id'] ?>" name="tags[]" value="<?= $tag['id'] ?>"
                    <?php if(in_array($tag['id'], $args['post']['tags'] ?? [])) echo 'checked'; ?>>
                <label for="post-edit-tag<?= $tag['id'] ?>"><?= $tag['name'] ?></label>
            <?php endforeach; ?>
        </div>

        <div class="post-edit-field">
            <label for="post-edit-status">Status:</label>
            <select id="post-edit-status" name="status" required>
                <option value="draft" <?= isset($args['post']['status']) && $args['post']['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="pending_approval" <?= isset($args['post']['status']) && $args['post']['status'] == 'pending_approval' ? 'selected' : '' ?>>Pending Approval</option>
                <option value="approved" <?= isset($args['post']['status']) && $args['post']['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="published" <?= isset($args['post']['status']) && $args['post']['status'] == 'published' ? 'selected' : '' ?>>Published</option>
            </select>
        </div>

        <div class="post-edit-field">
            <button type="submit" class="post-edit-btn">Save Post</button>
        </div>
    </form>
</div>
<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>
