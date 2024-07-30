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
    'title' => 'Bờ Lốc'
]);
?>
<style>
    .category-index-container {
        width: 90%;
        margin: auto;
    }

    .category-index-important-categories {
        margin-bottom: 20px;
    }

    .category-index-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: space-between;
    }

    .category-index-buttons a {
        flex: 1 1 calc(33.33% - 20px);
        margin: 5px 0;
    }

    .category-index-buttons a button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .category-index-buttons button:hover {
        background-color: #0056b3;
    }

    .category-index-category {
        margin-bottom: 30px;
    }

    .category-index-category-title {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .category-index-cards {
        display: flex;
        gap: 10px;
    }

    .category-index-card {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .category-index-card img {
        width: 300px;
        height: 300px;
    }

    .category-index-card-title {
        padding: 10px;
        font-size: 18px;
    }

    .category-index-see-more {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .category-index-all-categories {
        flex: 100% !important;
    }
</style>
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="category-index-container">
    <h1>Blog Categories</h1>
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
                <button>View all categories</button>
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
                        <div class="category-index-card-title">See More</div>
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
</div>
</body>
