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
use inc\models\Category;
use inc\models\Tag;
use inc\models\Banner;

$searchTerm = $args['search_term'];
$posts = $args['posts'];
$categories = $args['categories'];
$tags = $args['tags'];

// Pagination
list($currentPage, $postsPerPage, $validPostsPerPage) = Post::getPaginationParams();

$totalPosts = count($posts);
$totalCategories = count($categories);
$totalTags = count($tags);
$totalResults = $totalPosts + $totalCategories + $totalTags;

$totalPages = ceil($totalPosts / $postsPerPage);
$startIndex = ($currentPage - 1) * $postsPerPage;
$endIndex = min($startIndex + $postsPerPage, $totalPosts);
$currentPosts = array_slice($posts, $startIndex, $postsPerPage);

// Fetch banners
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');

Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Content related to "' . htmlspecialchars($searchTerm) . '"'
]);
?>

<style>
    .category-index-buttons, .tag-index-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: space-between;
    }

    .category-index-buttons a, .tag-index-buttons a {
        flex: 1 1 calc(33.33% - 20px);
        margin: 5px 0;
    }

    .category-index-buttons a button, .tag-index-buttons a button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .category-index-buttons button:hover, .tag-index-buttons button:hover {
        background-color: #0056b3;
    }

    .page-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .content-section {
        flex: 3;
        padding: 20px;
    }

    .side-banner-section {
        flex: 1;
        padding: 20px;
    }

    .header-banner img, .footer-banner img, .side-banner-section img {
        width: 100%;
        height: auto;
    }
</style>

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
            <script>
                function updatePerPage(value) {
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('per_page', value);
                    urlParams.set('page', 1); // Reset to page 1 when changing per_page
                    window.location.search = urlParams.toString();
                }
            </script>
            <div class="sort-per-page-container">
                <label for="sort-per-page-select">Posts per page:</label>
                <select id="sort-per-page-select" class="sort-per-page-select" onchange="updatePerPage(this.value)">
                    <?php foreach ($validPostsPerPage as $value): ?>
                        <option value="<?php echo $value; ?>" <?php if ($value == $postsPerPage) echo 'selected'; ?>>
                            <?php echo $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h1>Content related to "<?= htmlspecialchars($searchTerm) ?>"</h1>
            <p><?= $totalResults ?> result(s) found</p>

            <?php if ($totalCategories > 0): ?>
                <section class="category-index-important-categories">
                    <h2>Categories (<?= $totalCategories ?>)</h2>
                    <div class="category-index-buttons">
                        <?php foreach ($categories as $category): ?>
                            <a href="/category/<?= $category['slug']; ?>">
                                <button>
                                    <?= $category['name']; ?>
                                </button>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($totalTags > 0): ?>
                <section class="tag-index-important-tags">
                    <h2>Tags (<?= $totalTags ?>)</h2>
                    <div class="tag-index-buttons">
                        <?php foreach ($tags as $tag): ?>
                            <a href="/tag/<?= $tag['slug']; ?>">
                                <button>
                                    <?= $tag['name']; ?>
                                </button>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($totalPosts > 0): ?>
                <section class="post-index-important-posts">
                    <h2>Posts (<?= $totalPosts ?>)</h2>
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
                                                <button class="post-detail-tag-button"><?= $category['name'] ?></button>
                                            <?php endforeach; ?>
                                        </span>
                                    </div>
                                    <div class="post-tags">Tags:
                                        <span>
                                            <?php foreach ($tags as $tag): ?>
                                                <button class="post-detail-tag-button"><?= $tag['name'] ?></button>
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
                </section>
            <?php else: ?>
                <p>No related posts found for "<?= htmlspecialchars($searchTerm) ?>".</p>
                <a href="/" class="return-home-button">Return to Homepage</a>
            <?php endif; ?>
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
