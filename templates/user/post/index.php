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

// Phân trang
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
    'title' => 'Danh sách bài viết'
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

        <!-- Nội dung chính -->
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
    <?php Common::requireTemplate('user/layouts/footer.php'); ?>
</div>

</body>
