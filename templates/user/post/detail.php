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
use inc\models\Like;

$post_id = $_GET['post_id'] ?? null;
$post = PostModel::getPostById($post_id);

$categories = CommonPost::getPostCategories($post['post_id'] ?? null);
$tags = CommonPost::getPostTags($post['post_id'] ?? null);
$user = Common::getFrontendUser();
$author = User::getUserByAuthorId($post['author_id'] ?? null);

$liked = false;
if (isset($user['id'])) {
    $likeModel = new Like();
    $liked = (bool)$likeModel->checkLike($user['id'], $post_id);
}

Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Post Detail'
]);
?>

<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

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
        <?php if (!empty($post['banner_path'])): ?>
            <img src="<?= $post['banner_path'] ?>" alt="banner">
        <?php else: ?>
            <img src="<?= Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="post-banner">
        <?php endif; ?>
    </div>

    <div class="post-detail-content">
        <?= $post['content'] ?>
    </div>

    <div class="post-detail-author-card">
        <?php if (!empty($author['image'])): ?>
            <img src="<?= 'image' ?>" alt="Author Profile" class="post-detail-author-profile-image">
        <?php else: ?>
            <img src="<?= Common::getAssetPath('images/avatar.webp') ?>" alt="Author Profile" class="post-detail-author-profile-image">
        <?php endif; ?>
        <div class="post-detail-author-details">
            <h4><?= $author['username'] ?></h4>
            <p><?= 'bio' ?></p>
        </div>
    </div>

    <div class="post-detail-footer">
        <button class="post-detail-like-button" data-liked="<?= $liked ? 'true' : 'false' ?>" onclick="likePost(<?= $post_id ?>)">
            <?= $liked ? 'Liked' : 'Like' ?>
        </button>
        <span class="post-detail-like-count" id="like-count"><?= $post['likes'] ?? 0 ?> Likes</span>
    </div>
    <?php Common::requireTemplate('user/comment/post_block.php', ['post_id' => $post['post_id'] ?? null, 'user' => Common::getFrontendUser() ? Common::getFrontendUser() : null  ]); ?>
</div>

<script src="<?= Common::getAssetPath('js/user/post/like-script.js') ?>"></script>
<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>