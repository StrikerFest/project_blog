<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', ['title' => 'burogu']);

$current_user = Common::getCurrentBackendUser();
$post = $args['post'];
$post_category_ids = $post['categories'] !== null ? explode(',', $post['categories']) : null;
$post_tag_ids = $post['tags'] !== null ? explode(',', $post['tags']) : null;

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
                $post_tag_ids = $post['tags'] !== null ? explode(',', $post['tags']) : null;
                foreach ($args['tags'] as $tag): ?>
                    <option value="<?= $tag['tag_id'] ?>"
                        <?= (in_array($tag['tag_id'], $post_tag_ids ?? [])) ? 'selected' : '' ?>>
                        <?= $tag['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="post-edit-field">
            <label for="post-edit-status">Status:</label>
            <select id="post-edit-status" name="status" required>
                <?php
                $currentStatus = $post['status'] ?? null; // Get current status

                // Define available statuses based on current status and user role
                $availableStatuses = [];
                switch ($current_user['role']) {
                    case 'writer':
                        if (!$currentStatus) { // New post
                            $availableStatuses = ['draft', 'pending_approval'];
                        } else {
                            switch ($currentStatus) {
                                case 'draft':
                                case 'approval_retracted':
                                case 'approval_denied':
                                    $availableStatuses = ['pending_approval'];
                                    break;
                                // No other options for writers
                            }
                        }
                        break;
                    case 'editor':
                        if (!$currentStatus) { // New post (shouldn't happen for editors)
                            $availableStatuses = ['draft']; // Restrict to draft for editors on new posts
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
                                // No other options for editors
                            }
                        }
                        break;
                    case 'admin':
                        // Enforce sequence even for admins
                        if (!$currentStatus) {
                            $availableStatuses = ['draft']; // New post starts with draft
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
                                    // Cannot move from denied or retracted approval
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
                                // No other options for admins
                            }
                        }
                        break;
                    default:
                        // Handle invalid role
                        echo '<option value="">Invalid Role</option>';
                        break;
                }

                // Generate options based on available statuses
                foreach ($availableStatuses as $status) {
                    $isSelected = ($currentStatus === $status) ? 'selected' : '';
                    echo "<option value='$status' $isSelected>$status</option>";
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
