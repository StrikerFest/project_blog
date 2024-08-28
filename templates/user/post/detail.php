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

use inc\models\Banner;
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Chi tiết bài viết'
]);
?>

<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Phần trái 3/4 -->
    <div class="content-section">
        <!-- Banner tiêu đề -->
        <div class="header-banner">
            <?php Common::requireTemplate('user/layouts/header_banner.php', [
                'banner_image' => $headerBanner
            ]); ?>
        </div>

        <!-- Nội dung chính chi tiết bài viết -->
        <div class="post-detail-container">
            <div class="post-detail-breadcrumb">
                <a href="index.php">Trang chủ</a> > <a href="posts.php">Bài viết</a> > <?= $post['title'] ?>
            </div>

            <h1 class="post-detail-title"><?= $post['title'] ?></h1>

            <h3 class="post-detail-categories">Danh mục:
                <?php foreach ($categories as $key => $category): ?>
                    <span><?= $category['name'] ?></span>
                    <?php
                    if($key != (count($categories) - 1)) {
                        echo ", ";
                    }
                endforeach;
                ?>
            </h3>

            <div class="post-detail-tags">
                <?php foreach ($tags as $tag): ?>
                    <button class="post-detail-tag-button"><?= $tag['name'] ?></button>
                <?php endforeach; ?>
            </div>

            <div class="post-detail-banner">
                <?php if (!empty($post['banner_path'])): ?>
                    <img src="<?= $post['banner_path'] ?>" alt="banner">
                <?php else: ?>
                    <img src="<?= Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="banner-bài-viết">
                <?php endif; ?>
            </div>

            <div class="post-detail-content">
                <?= $post['content'] ?>
            </div>

            <div class="post-detail-author-card">
                <?php if (!empty($author['image'])): ?>
                    <img src="<?= $author['image'] ?>" alt="Hồ sơ tác giả" class="post-detail-author-profile-image">
                <?php else: ?>
                    <img src="<?= Common::getAssetPath('images/avatar.webp') ?>" alt="Hồ sơ tác giả" class="post-detail-author-profile-image">
                <?php endif; ?>
                <div class="post-detail-author-details">
                    <h4><?= $author['username'] ?></h4>
                    <p><?= 'bio' ?></p>
                </div>
            </div>

            <div class="post-detail-footer">
                <button class="post-detail-like-button" data-liked="<?= $liked ? 'true' : 'false' ?>" onclick="likePost(<?= $post_id ?>)">
                    <?= $liked ? 'Đã thích' : 'Thích' ?>
                </button>
                <span class="post-detail-like-count" id="like-count"><?= $post['likes'] ?? 0 ?> Lượt thích</span>
            </div>
            <?php Common::requireTemplate('user/comment/post_block.php', ['post_id' => $post['post_id'] ?? null, 'user' => Common::getFrontendUser() ? Common::getFrontendUser() : null]); ?>
        </div>

        <!-- Banner chân trang -->
        <div class="footer-banner">
            <?php Common::requireTemplate('user/layouts/footer_banner.php', [
                'banner_image' => $footerBanner
            ]); ?>
        </div>
    </div>

    <!-- Phần bên phải 1/4 (Thanh bên) -->
    <div class="side-banner-section">
        <?php Common::requireTemplate('user/layouts/side_banner_right.php', [
            'banner_image' => $sideBanner
        ]); ?>
    </div>
</div>

<script src="<?= Common::getAssetPath('js/user/post/like-script.js') ?>"></script>
<script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>
