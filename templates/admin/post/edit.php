<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', ['title' => 'burogu']); 
?>

<body>
    <div class="container">
        <?php if(isset($args['post']['id'])): ?>
        <h1>Update Post ID: <?= $args['post']['id'] ?></h1>
        <?php else: ?>
        <h1>Create New Post</h1>
        <?php endif; ?>
        <form id="createPostForm" method="POST">
            <input type="hidden" value="<?= $args['post']['id'] ?? '' ?>" name="id" />
            <input type="text" value="<?= $args['post']['title'] ?? '' ?>" name="title" placeholder="Title" required>
            <textarea name="content" placeholder="Content" required><?= $args['post']['content'] ?? '' ?></textarea>
            <button type="submit" class="btn">Save Post</button>
        </form>
    </div>
    <script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>