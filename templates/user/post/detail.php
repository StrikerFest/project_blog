<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\helpers\admin\Post as CommonPost;
use inc\models\Post as PostModel;
use inc\models\User;

$post_id = $_GET['post_id'] ?? null;
$post = PostModel::getPostById($post_id);

$categories = CommonPost::getPostCategories($post['post_id'] ?? null);
$tags = CommonPost::getPostTags($post['post_id'] ?? null);


$author = User::getUserByAuthorId($post['author_id'] ?? null);

Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Post Detail'
]);
?>
<body>
<div class="post-detail-breadcrumb">
    <a href="index.php">Home</a> > <a href="posts.php">Posts</a> > <?= $post['title'] ?>
</div>

<div class="post-detail-container">
    <h1 class="post-detail-title"><?= $post['title'] ?></h1>

    <h3 class="post-detail-categories">Categories: <?= implode(', ', $categories) ?></h3>

    <div class="post-detail-tags">
        <?php foreach ($tags as $tag): ?>
            <button class="post-detail-tag-button"><?= $tag ?></button>
        <?php endforeach; ?>
    </div>

    <div class="post-detail-banner">
        <?php if (!empty($post['banner'])): ?>
            <img src="<?= $post['banner'] ?>" alt="banner">
        <?php else: ?>
            <div class="post-detail-banner-placeholder"></div>
        <?php endif; ?>
    </div>

    <div class="post-detail-content">
        <?= $post['content'] ?>
    </div>
    
    <div class="post-detail-author-card">
        <img src="<?= 'image' ?>" alt="Author Profile" class="post-detail-author-profile-image">
        <div class="post-detail-author-details">
            <h4><?= $author['username'] ?></h4>
            <p><?= 'bio' ?></p>
        </div>
    </div>

    <div class="post-detail-footer">
        <button class="post-detail-like-button" onclick="likePost(<?= $post_id ?>)">Like</button>
        <span class="post-detail-like-count" id="like-count"><?= $post['likes'] ?? 0 ?> Likes</span>
    </div>
</div>

<script>
    function likePost(postId) {
        // Mock function for liking a post
        let likeCountElem = document.getElementById('like-count');
        let currentLikes = parseInt(likeCountElem.innerText);
        likeCountElem.innerText = (currentLikes + 1) + ' Likes';
    }
</script>

<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>