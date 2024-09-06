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
    <div class="content-section">
        <div class="header-banner">
            <?php Banner::getBannerTemplate('user/layouts/header_banner.php', $headerBanner); ?>
        </div>
        
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

        <div class="footer-banner">
            <?php Banner::getBannerTemplate('user/layouts/footer_banner.php', $footerBanner); ?>
        </div>
    </div>

    <div class="side-banner-section">
        <?php Banner::getBannerTemplate('user/layouts/side_banner_right.php', $sideBanner); ?>
    </div>
    <?php Common::requireTemplate('user/layouts/footer.php'); ?>
</div>

</body>
