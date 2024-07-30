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
<style>
    .all-categories-container {
        width: 90%;
        margin: auto;
    }

    .all-categories-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .all-categories-list-item {
        flex: 1 1 calc(33.33% - 20px);
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        text-align: center;
        font-size: 18px;
    }

    .all-categories-list-item a {
        text-decoration: none;
        color: #007BFF;
    }

    .all-categories-list-item a:hover {
        text-decoration: underline;
    }
</style>
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
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
</body>
