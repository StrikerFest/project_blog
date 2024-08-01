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
$posts = Post::getPostsByCategoryIdWithPagination($category_id, $offset, $limit);
$totalPosts = Post::countPostsByCategoryId($category_id);
$totalPages = ceil($totalPosts / $limit);

// Header
Common::requireTemplate('user/layouts/headers.php', [
    'title' => $category['name']
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/user/category/detail.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
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
    <a href="/category" class="back-button">Back to Categories</a>
</div>
</body>
