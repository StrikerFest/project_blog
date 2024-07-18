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
