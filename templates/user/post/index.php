<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\helpers\admin\Post as CommonPost;
use inc\helpers\user\Post;

$posts = $args['posts'];

// Pagination
list($currentPage, $postsPerPage, $validPostsPerPage) = Post::getPaginationParams();

$totalPosts = count($posts);
$totalPages = ceil($totalPosts / $postsPerPage);
$startIndex = ($currentPage - 1) * $postsPerPage;
$endIndex = min($startIndex + $postsPerPage, $totalPosts);
$currentPosts = array_slice($posts, $startIndex, $postsPerPage);

use inc\models\Banner;

$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);

?>
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="post-container">
                <?php
                foreach ($currentPosts as $post) {
                    $categories = CommonPost::getPostCategories($post['post_id'] ?? null);
                    $tags = CommonPost::getPostTags($post['post_id'] ?? null);
                    Common::requireTemplate('user/post/components/post-card.php', [
                        'post' => $post,
                        'categories' => $categories,
                        'tags' => $tags
                    ]);
                }
                ?>
            </div>

            <div class="paginate-bar">
                <?php echo Post::generatePagination($currentPage, $totalPages, $postsPerPage); ?>
            </div>
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
