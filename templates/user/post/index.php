<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
<body>
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

<div class="main-content">
    <div class="post-container">
        <?php foreach ($currentPosts as $post): ?>
            <div class="post-card">
                <a href="post_detail.php?post_id=<?= $post['post_id'] ?>" class="post-thumbnail">
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?php echo $post['image']; ?>" alt="image">
                    <?php else: ?>
                        <div class="post-thumbnail-placeholder"></div>
                    <?php endif; ?>
                </a>
                <div class="post-content">
                    <a href="post_detail.php?post_id=<?= $post['post_id'] ?>" class="post-title"><?php echo $post['title']; ?></a>
                    <div class="post-category">Category: INSERT</div>
                    <div class="post-tags">Tags: INSERT</div>
                    <a href="post_detail.php?post_id=<?= $post['post_id'] ?>" class="post-see-more">See More</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="paginate-bar">
        <?php echo Post::generatePagination($currentPage, $totalPages, $postsPerPage); ?>
    </div>
</div>

</body>
