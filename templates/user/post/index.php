<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\helpers\user\Post;

$posts = $args['posts'];

// Phan trang
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Index Page</title>
    <style>
        .post-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        .post-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 16px;
            border-radius: 8px;
            background-color: #fff;
            width: calc(50% - 16px); /* 2 columns with 16px gap */
            box-sizing: border-box;
        }
        .post-card img {
            max-width: 100%;
            border-radius: 8px 8px 0 0;
        }
        .post-card-title {
            font-size: 1.5em;
            margin: 8px 0;
        }
        .post-card-content {
            font-size: 1em;
            color: #666;
        }
        .paginate-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        .paginate-bar a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }
        .paginate-bar a.current {
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
        }
        .sort-per-page-container {
            text-align: center;
            margin: 20px 0;
        }
        .sort-per-page-select {
            padding: 8px;
            font-size: 1em;
        }
    </style>
    <script>
        function updatePerPage(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('per_page', value);
            urlParams.set('page', 1); // Reset to page 1 when changing per_page
            window.location.search = urlParams.toString();
        }
    </script>
</head>
<body>

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

<div class="post-container">
    <?php foreach ($currentPosts as $post): ?>
        <div class="post-card">
            <img src="<?php echo $post['image'] ?? ''; ?>" alt="<?php echo 'image'; ?>" class="post-card-image">
            <div class="post-card-title"><?php echo $post['title']; ?></div>
            <div class="post-card-content"><?php echo $post['content']; ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="paginate-bar">
    <?php echo Post::generatePagination($currentPage, $totalPages, $postsPerPage); ?>
</div>

</body>
</html>
