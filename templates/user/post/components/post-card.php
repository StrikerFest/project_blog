<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

$post = $args['post'];
$categories = $args['categories'];
$tags = $args['tags'];
?>
<div class="post-card">
    <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-thumbnail">
        <?php if (!empty($post['thumbnail_path'])): ?>
            <img src="<?= $post['thumbnail_path']; ?>" alt="image">
        <?php else: ?>
            <img src="<?= Common::getAssetPath('images/placeholder-thumbnail.webp') ?>" alt="post-thumbnail">
        <?php endif; ?>
    </a>
    <div class="post-content">
        <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-title"><?php echo $post['title']; ?></a>
        <div class="post-category">Category:
            <span>
        <?php foreach ($categories as $category): ?>
            <a href="/category/<?= $category['slug']; ?>" class="post-detail-tag-button"><?= $category['name'] ?></a>
        <?php endforeach; ?>
    </span>
        </div>
        <div class="post-tags">Tags:
            <span>
        <?php foreach ($tags as $tag): ?>
            <a href="/tag/<?= $tag['slug']; ?>" class="post-detail-tag-button"><?= $tag['name'] ?></a>
        <?php endforeach; ?>
    </span>
        </div>
        <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-see-more">See More</a>
    </div>
</div>
