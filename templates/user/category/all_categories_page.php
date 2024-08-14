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

// Header
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'All Categories'
]);

// Fetch all categories
$categories = Category::getCategories();

// Fetch number of posts for each category
$category_post_counts = [];
foreach ($categories as $category) {
    $category_id = $category['category_id'];
    $category_post_counts[$category_id] = Post::countPostsByCategoryId($category_id);
}

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/category/all_categories_page.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>

<div class="page-container">
    <!-- Left 3/4 Section -->
    <div class="content-section">
        <!-- Header Banner -->
        <div class="header-banner">
            <?php Common::requireTemplate('user/layouts/header_banner.php', [
                'banner_image' => Common::getAssetPath('images/line.jpg') // Replace with dynamic banner path
            ]); ?>
        </div>

        <div class="all-categories-container">
            <h1>All Categories</h1>
            <div class="all-categories-list">
                <?php foreach ($categories as $category) : ?>
                    <div class="all-categories-list-item">
                        <a href="/category/<?= $category['slug']; ?>">
                            <?= $category['name']; ?> [<?= $category_post_counts[$category['category_id']]; ?>]
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Footer Banner -->
        <div class="footer-banner">
            <?php Common::requireTemplate('user/layouts/footer_banner.php', [
                'banner_image' => Common::getAssetPath('images/line.jpg') // Replace with dynamic banner path
            ]); ?>
        </div>
    </div>

    <!-- Right 1/4 Section (Sidebar) -->
    <div class="side-banner-section">
        <?php Common::requireTemplate('user/layouts/side_banner_right.php', [
            'banner_image' => Common::getAssetPath('images/300x1270_placeholder_banner.webp') // Replace with dynamic banner path
        ]); ?>
    </div>
</div>

</body>
