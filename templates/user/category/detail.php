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

$category_slug = $args['route_args']['category_slug'] ?? null;
$category_id = Category::getCategoryIdBySlug($category_slug);

$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$category = Category::getCategoryById($category_id);

if ($category === null) {
    header("Location: /post");
    exit();
}

$posts = Post::getPostsByCategoryIdWithPagination($category_id, $offset, $limit);
$totalPosts = Post::countPostsByCategoryId($category_id);
$totalPages = ceil($totalPosts / $limit);

use inc\models\Banner;
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => $category['name']
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/category/detail.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Phần 3/4 bên trái -->
    <div class="content-section">
        <!-- Biểu ngữ trên cùng -->
        <div class="header-banner">
            <?php Common::requireTemplate('user/layouts/header_banner.php', [
                'banner_image' => $headerBanner
            ]); ?>
        </div>

        <div class="category-detail-container">
            <div class="category-detail-header">
                <h1><?= $category['name']; ?></h1>
                <p><?= $category['description']; ?></p>
            </div>
            <section class="category-detail-posts">
                <?php foreach ($posts as $post) : ?>
                    <div class="category-detail-post-card">
                        <img src="<?= $post['thumbnail_path']; ?>" alt="<?= $post['title']; ?>">
                        <div class="category-detail-post-card-title"><?= $post['title']; ?></div>
                    </div>
                <?php endforeach; ?>
            </section>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?id=<?= $category_id; ?>&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>
            </div>
            <a href="/category" class="back-button">Quay lại danh mục</a>
        </div>

        <!-- Biểu ngữ dưới cùng -->
        <div class="footer-banner">
            <?php Common::requireTemplate('user/layouts/footer_banner.php', [
                'banner_image' => $footerBanner
            ]); ?>
        </div>
    </div>

    <!-- Phần 1/4 bên phải (Thanh bên) -->
    <div class="side-banner-section">
        <?php Common::requireTemplate('user/layouts/side_banner_right.php', [
            'banner_image' => $sideBanner
        ]); ?>
    </div>
</div>

</body>
