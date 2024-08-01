<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\models\Tag;
use inc\models\Post;

// Header
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'All Tags'
]);

// Fetch all tags
$tags = Tag::getTags();

// Fetch number of posts for each tag
$tag_post_counts = [];
foreach ($tags as $tag) {
    $tag_id = $tag['tag_id'];
    $tag_post_counts[$tag_id] = Post::countPostsByTagId($tag_id);
}

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/tag/all_tags_page.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="all-tags-container">
    <h1>All Tags</h1>
    <div class="all-tags-list">
        <?php foreach ($tags as $tag) : ?>
            <div class="all-tags-list-item">
                <a href="/tag/<?= $tag['slug']; ?>">
                    <?= $tag['name']; ?> [<?= $tag_post_counts[$tag['tag_id']]; ?>]
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
