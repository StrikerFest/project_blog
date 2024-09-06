<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;

use inc\models\Banner;
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/category/index.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Phần 3/4 bên trái -->
    <div class="content-section">
        <!-- Biểu ngữ trên cùng -->
        <div class="header-banner">
            <?php Banner::getBannerTemplate('user/layouts/header_banner.php', $headerBanner); ?>
        </div>

        <div class="category-index-container">
            <h1>Danh mục Blog</h1>
            <section class="category-index-important-categories">
                <div class="category-index-buttons">
                    <?php
                    $category_ids = Category::getCategoriesByPosition(9);
                    $category_posts = [];
                    $categories = [];

                    foreach ($category_ids as $category_id) {
                        $category_posts[$category_id] = Post::getPostsByCategoryId($category_id);
                        $categories[] = Category::getCategoryById($category_id);
                    }
                    ?>
                    <?php foreach ($categories as $category) : ?>
                        <a href="/category/<?= $category['slug']; ?>">
                            <button>
                                <?= $category['name']; ?>
                            </button>
                        </a>
                    <?php endforeach; ?>
                    <a href="/category-all" class="category-index-all-categories">
                        <button>Xem tất cả danh mục</button>
                    </a>
                </div>
            </section>
            <section class="category-index-categories">
                <?php foreach ($categories as $category) : ?>
                    <?php if (!empty($category_posts[$category['category_id']])) : ?>
                        <a href="/category/<?= $category['slug']; ?>"><h2 class="category-index-category-title"><?= $category['name']; ?></h2></a>
                        <div class="category-index-cards">
                            <?php foreach ($category_posts[$category['category_id']] as $post) : ?>
                                <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="category-index-card">
                                    <img src="<?= empty($post['thumbnail_path']) ? PH_THUMBNAIL : $post['thumbnail_path'] ?>" alt="<?= $post['title']; ?>">
                                    <div class="category-index-card-title"><?= $post['title']; ?></div>
                                </a>
                            <?php endforeach; ?>
                            <a class="category-index-card category-index-see-more" href="/category/<?= $category['slug'] ?>">
                                <div class="category-index-card-title">Xem thêm</div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>
        </div>

        <!-- Biểu ngữ dưới cùng -->
        <div class="footer-banner">
            <?php Banner::getBannerTemplate('user/layouts/footer_banner.php', $footerBanner); ?>
        </div>
    </div>

    <!-- Phần 1/4 bên phải (Thanh bên) -->
    <div class="side-banner-section">
        <?php Banner::getBannerTemplate('user/layouts/side_banner_right.php', $sideBanner); ?>
    </div>
</div>

</body>
