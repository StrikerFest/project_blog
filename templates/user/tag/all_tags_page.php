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
use inc\models\Banner;

$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Tất cả các thẻ'
]);

// Lấy tất cả các thẻ
$tags = Tag::getTags();

// Lấy số lượng bài viết cho từng thẻ
$tag_post_counts = [];
foreach ($tags as $tag) {
    $tag_id = $tag['tag_id'];
    $tag_post_counts[$tag_id] = Post::countPostsByTagId($tag_id);
}

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/tag/all_tags_page.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Phần trái 3/4 -->
    <div class="content-section">
        <!-- Banner tiêu đề -->
        <div class="header-banner">
            <?php Banner::getBannerTemplate('user/layouts/header_banner.php', $headerBanner); ?>
        </div>

        <div class="all-tags-container">
            <h1>Tất cả các thẻ</h1>
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
