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
<style>
    .all-tags-container {
        width: 90%;
        margin: auto;
    }

    .all-tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .all-tags-list-item {
        flex: 1 1 calc(33.33% - 20px);
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        text-align: center;
        font-size: 18px;
    }

    .all-tags-list-item a {
        text-decoration: none;
        color: #007BFF;
    }

    .all-tags-list-item a:hover {
        text-decoration: underline;
    }
</style>
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
