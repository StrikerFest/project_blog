<?php

use inc\helpers\Common;
use inc\helpers\Post;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', ['title' => 'burogu']);

$current_user = Common::getCurrentBackendUser();
$post = $args['post'];

$post_category_ids = Post::getPostCategories($post['post_id'] ?? null,'id');
$post_tag_ids = Post::getPostTags($post['post_id'] ?? null, 'id');

$allowed = Post::canChangeStatus($post['status'] ?? null, $current_user['role']);
$permissionMissing = !$allowed ? "You don't have permission to change post status." : '';

?>

<body>
<div class="post-edit-container">
    <?php if (isset($post['id'])): ?>
        <h1 class="post-edit-title">Update Post ID: <?= $post['id'] ?></h1>
    <?php else: ?>
        <h1 class="post-edit-title">Create New Post</h1>
    <?php endif; ?>
    <form id="post-edit-createPostForm" method="POST">
        <input type="hidden" value="<?= $post['post_id'] ?? '' ?>" name="id"/>
        <input type="hidden" value="<?= $current_user['id'] ?? $args['current_user_id'] ?>" name="author_id"/>
        <input type="hidden" value="<?= $post['approved_by'] ?? '' ?>" name="approved_by"/>

        <div class="post-edit-field">
            <label for="post-edit-title">Title:</label>
            <input type="text" id="post-edit-title" name="title" value="<?= $post['title'] ?? '' ?>" placeholder="Title" maxlength="255" required>
        </div>

        <div class="post-edit-field">
            <label for="post-edit-content">Content:</label>
            <textarea id="post-edit-content" name="content" placeholder="Content" required><?= $post['content'] ?? '' ?></textarea>
        </div>

        <div class="post-edit-field">
            <label>Categories:</label>
            <select name="categories[]" id="post-edit-categories" multiple required>
                <?php foreach ($args['categories'] as $category): ?>
                    <option value="<?= $category['category_id'] ?>"
                        <?= (in_array($category['category_id'], $post_category_ids ?? [])) ? 'selected' : '' ?>>
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="post-edit-field">
            <label>Tags:</label>
            <select name="tags[]" id="post-edit-tags" multiple required>
                <?php
                foreach ($args['tags'] as $tag): ?>
                    <option value="<?= $tag['tag_id'] ?>"
                        <?= (in_array($tag['tag_id'], $post_tag_ids ?? [])) ? 'selected' : '' ?>>
                        <?= $tag['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="post-edit-field">
            <label for="post-edit-status">Status: <?= $permissionMissing ?></label>
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
                            $availableStatuses = ['draft']; // Editor ko tao post dc
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
                        echo '<option value="">Invalid Role</option>';
                        break;
                }


                echo "<option value='$currentStatus' selected>$currentStatus</option>";
                
                foreach ($availableStatuses as $status) {
                    if ($currentStatus === $status){
                        continue;
                        }
                    echo "<option value='$status'>$status</option>";
                }
                ?>
            </select>
        </div>

        <div class="post-edit-field">
            <button type="submit" class="post-edit-btn">Save Post</button>
        </div>
    </form>
</div>
<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>
