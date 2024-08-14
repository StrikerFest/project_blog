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
    <!-- Left 3/4 Section -->
    <div class="content-section">
        <!-- Header Banner -->
        <div class="header-banner">
            <?php Common::requireTemplate('user/layouts/header_banner.php', [
                'banner_image' => $headerBanner
            ]); ?>
        </div>

        <div class="tag-detail-container">
            <div class="tag-detail-header">
                <h1><?= $tag['name']; ?></h1>
            </div>
            <section class="tag-detail-posts">
                <?php foreach ($posts as $post) : ?>
                    <div class="tag-detail-post-card">
                        <img src="<?= $post['thumbnail_path']; ?>" alt="<?= $post['title']; ?>">
                        <div class="tag-detail-post-card-title"><?= $post['title']; ?></div>
                    </div>
                <?php endforeach; ?>
            </section>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?id=<?= $tag_id; ?>&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>
            </div>
            <a href="/tag" class="back-button">Back to Tags</a>
        </div>

        <!-- Footer Banner -->
        <div class="footer-banner">
            <?php Common::requireTemplate('user/layouts/footer_banner.php', [
                'banner_image' => $footerBanner
            ]); ?>
        </div>
    </div>

    <!-- Right 1/4 Section (Sidebar) -->
    <div class="side-banner-section">
        <?php Common::requireTemplate('user/layouts/side_banner_right.php', [
            'banner_image' => $sideBanner
        ]); ?>
    </div>
</div>

</body>
