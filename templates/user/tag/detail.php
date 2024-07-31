<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\models\Tag;
use inc\models\Post;

$tag_slug = $args['route_args']['tag_slug'] ?? null;
$tag_id = Tag::getTagIdBySlug($tag_slug);

$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$tag = Tag::getTagById($tag_id);
$posts = Post::getPostsByTagIdWithPagination($tag_id, $offset, $limit);
$totalPosts = Post::countPostsByTagId($tag_id);
$totalPages = ceil($totalPosts / $limit);

// Header
Common::requireTemplate('user/layouts/headers.php', [
    'title' => $tag['name']
]);
?>
<style>
    .tag-detail-container {
        width: 90%;
        margin: auto;
    }

    .tag-detail-header {
        margin-bottom: 20px;
    }

    .tag-detail-header h1 {
        font-size: 32px;
    }

    .tag-detail-header p {
        font-size: 18px;
        color: #666;
    }

    .tag-detail-posts {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .tag-detail-post-card {
        flex: 1 1 calc(33.33% - 20px);
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .tag-detail-post-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .tag-detail-post-card-title {
        padding: 10px;
        font-size: 20px;
    }

    .back-button {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }

    .back-button:hover {
        background-color: #0056b3;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a {
        padding: 10px 15px;
        margin: 0 5px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .pagination a:hover {
        background-color: #0056b3;
    }

    .pagination .active {
        background-color: #0056b3;
        pointer-events: none;
    }
</style>
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="tag-detail-container">
    <div class="tag-detail-header">
        <h1><?= $tag['name']; ?></h1>
    </div>
    <section class="tag-detail-posts">
        <?php foreach ($posts as $post) : ?>
            <div class="tag-detail-post-card">
                <img src="<?= $post['thumbnail_path']; ?>" alt="<?= $post['title']; ?>">
                <div class="tag-detail-post-card-title"><?= $post['title']; ?></div>
            </div>
        <?php endforeach; ?>
    </section>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <a href="?id=<?= $tag_id; ?>&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
    <a href="/tag" class="back-button">Back to Tags</a>
</div>
</body>
