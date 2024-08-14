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

// Header
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
                'banner_image' => Common::getAssetPath('images/line.jpg') // Replace with dynamic banner path
            ]); ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="post-container">
                <?php foreach ($currentPosts as $post): ?>
                    <?php
                    $categories = CommonPost::getPostCategories($post['post_id'] ?? null);
                    $tags = CommonPost::getPostTags($post['post_id'] ?? null);
                    ?>
                    <div class="post-card">
                        <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-thumbnail">
                            <?php if (!empty($post['thumbnail_path'])): ?>
                                <img src="<?= $post['thumbnail_path']; ?>" alt="image">
                            <?php else: ?>
                                <img src="<?= Common::getAssetPath('images/placeholder-thumbnail.webp') ?>" alt="post-thumbnail">
                            <?php endif; ?>
                        </a>
                        <div class="post-content">
                            <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-title"><?php echo $post['title']; ?></a>
                            <div class="post-category">Category:
                                <span>
                                    <?php foreach ($categories as $category): ?>
                                        <button class="post-detail-tag-button"><?= $category ?></button>
                                    <?php endforeach; ?>
                                </span>
                            </div>
                            <div class="post-tags">Tags:
                                <span>
                                    <?php foreach ($tags as $tag): ?>
                                        <button class="post-detail-tag-button"><?= $tag ?></button>
                                    <?php endforeach; ?>
                                </span>
                            </div>
                            <a href="/post/show?post_id=<?= $post['post_id'] ?>" class="post-see-more">See More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="paginate-bar">
                <?php echo Post::generatePagination($currentPage, $totalPages, $postsPerPage); ?>
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
