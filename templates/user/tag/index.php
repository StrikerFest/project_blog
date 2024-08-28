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
    'title' => 'Danh sách thẻ'
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/tag/index.css') ?>">
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

        <div class="tag-index-container">
            <h1>Danh sách thẻ Blog</h1>
            <section class="tag-index-important-tags">
                <div class="tag-index-buttons">
                    <?php
                    $tag_ids = Tag::getTagsByPosition(9);
                    $tag_posts = [];
                    $tags = [];

                    foreach ($tag_ids as $tag_id) {
                        $tag_posts[$tag_id] = Post::getPostsByTagId($tag_id);
                        $tags[] = Tag::getTagById($tag_id);
                    }
                    ?>
                    <?php foreach ($tags as $tag) : ?>
                        <a href="/tag/<?= $tag['slug']; ?>">
                            <button><?= $tag['name']; ?></button>
                        </a>
                    <?php endforeach; ?>
                    <a href="/tag-all" class="tag-index-all-tags">
                        <button>Xem tất cả thẻ</button>
                    </a>
                </div>
            </section>
            <section class="tag-index-tags">
                <?php foreach ($tags as $tag) : ?>
                    <?php if (!empty($tag_posts[$tag['tag_id']])) : ?>
                        <a href="/tag/<?= $tag['slug']; ?>">
                            <h2 class="tag-index-tag-title"><?= $tag['name']; ?></h2>
                        </a>
                        <div class="tag-index-cards">
                            <?php foreach ($tag_posts[$tag['tag_id']] as $post) : ?>
                                <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="tag-index-card">
                                    <img src="<?= empty($post['thumbnail_path']) ? PH_THUMBNAIL : $post['thumbnail_path'] ?>" alt="<?= $post['title']; ?>">
                                    <div class="tag-index-card-title"><?= $post['title']; ?></div>
                                </a>
                            <?php endforeach; ?>
                            <a class="tag-index-card tag-index-see-more" href="/tag/<?= $tag['slug'] ?>">
                                <div class="tag-index-card-title">Xem thêm</div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>
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

</body>
