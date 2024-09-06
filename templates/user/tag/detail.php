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

$tag_slug = $args['route_args']['tag_slug'] ?? null;
$tag_id = Tag::getTagIdBySlug($tag_slug);

$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$tag = Tag::getTagById($tag_id);

if ($tag === null) {
    header("Location: /post");
    exit();
}

$posts = Post::getPostsByTagIdWithPagination($tag_id, $offset, $limit);
$totalPosts = Post::countPostsByTagId($tag_id);
$totalPages = ceil($totalPosts / $limit);

use inc\models\Banner;
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => $tag['name']
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/tag/detail.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Phần trái 3/4 -->
    <div class="content-section">
        <!-- Banner tiêu đề -->
        <div class="header-banner">
            <?php Banner::getBannerTemplate('user/layouts/header_banner.php', $headerBanner); ?>
        </div>

        <div class="tag-detail-container">
            <div class="tag-detail-header">
                <h1><?= $tag['name']; ?></h1>
            </div>
            <section class="tag-detail-posts">
                <?php foreach ($posts as $post) : ?>
                <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="tag-index-card">
                    <div class="tag-detail-post-card">
                        <?php if (!empty($post['thumbnail_path'])): ?>
                            <img src="<?= $post['thumbnail_path']; ?>" alt="image">
                        <?php else: ?>
                            <img src="<?= Common::getAssetPath('images/placeholder-thumbnail.webp') ?>" alt="post-thumbnail">
                        <?php endif; ?>
                        <div class="tag-detail-post-card-title"><?= $post['title']; ?></div>
                    </div>
                </a>
                <?php endforeach; ?>
            </section>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?id=<?= $tag_id; ?>&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>
            </div>
            <a href="/tag" class="back-button">Quay lại các thẻ</a>
        </div>

        <!-- Banner chân trang -->
        <div class="footer-banner">
            <?php Banner::getBannerTemplate('user/layouts/footer_banner.php', $footerBanner); ?>
        </div>
    </div>

    <!-- Phần bên phải 1/4 (Thanh bên) -->
    <div class="side-banner-section">
        <?php Banner::getBannerTemplate('user/layouts/side_banner_right.php', $sideBanner); ?>
    </div>
</div>

</body>
