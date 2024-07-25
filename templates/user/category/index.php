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

    .category-index-buttons button {
        flex: 1 1 calc(33.33% - 20px);
        padding: 10px;
        margin: 5px 0;
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
        width: 100%;
        height: auto;
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
</style>
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="category-index-container">
    <section class="category-index-important-categories">
        <div class="category-index-buttons">
            <?php
            $categories = [
                'cat1' => 'Category 1',
                'cat2' => 'Category 2',
                'cat3' => 'Category 3',
                'cat4' => 'Category 4',
                'cat5' => 'Category 5',
                'cat6' => 'Category 6',
                'cat7' => 'Category 7',
                'cat8' => 'Category 8',
                'cat9' => 'Category 9'
            ];

            foreach ($categories as $key => $name) {
                echo "<button data-category=\"$key\">$name</button>";
            }
            ?>
        </div>
    </section>
    <section class="category-index-categories">
        <?php
        $posts = [
            'cat1' => [
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 1'],
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 2'],
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 3']
            ],
            'cat2' => [
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 1'],
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 2'],
                ['thumbnail' => 'https://via.placeholder.com/150', 'title' => 'Post 3']
            ],
            // Add more mock data for other categories
        ];

        foreach ($categories as $key => $name) {
            if (isset($posts[$key])) {
                echo "<div class=\"category-index-category\">";
                echo "<h2 class=\"category-index-category-title\">$name</h2>";
                echo "<div class=\"category-index-cards\">";
                foreach ($posts[$key] as $post) {
                    echo "<div class=\"category-index-card\">";
                    echo "<img src=\"{$post['thumbnail']}\" alt=\"{$post['title']}\">";
                    echo "<div class=\"category-index-card-title\">{$post['title']}</div>";
                    echo "</div>";
                }
                echo "<div class=\"category-index-card category-index-see-more\">";
                echo "<div class=\"category-index-card-title\">See More</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('.category-index-buttons button').on('click', function() {
            const categoryKey = $(this).data('category');
            $('.category-index-categories .category-index-category').hide();
            $(`.category-index-categories .category-index-category:has(h2:contains("${categoryKey.replace('cat', 'Category ')}"))`).show();
        });
    });
</script>
</body>
