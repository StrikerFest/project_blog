<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);

$currentPage = $_SERVER['REQUEST_URI'];
$logoutButton = '<a href="/logout"><button class="btn-logout">Logout</button></a>';

if (strstr($currentPage, '/admin')) {
    $logoutButton = '<a href="/admin/logout"><button class="btn-logout">Logout</button></a>';
}
?>

<body>
    <div class="logout">
        <?php echo $logoutButton; ?>
    </div>
    <div class="container">
        <h1>Simple Blog</h1>
        <a href="post/create" class="btn">Create New Post</a>
        <div id="blogPosts"></div>
    </div>
    <div class="container">
        <h1>Blog Posts</h1>
        <div id="blogPosts">
            <?php foreach ($args['posts'] as $post) : ?>
                <div class="post">
                    <h2><?= $post['title']; ?></h2>
                    <p><?= $post['content']; ?></p>
                    <a href="post/edit?id=<?= $post['id']; ?>" class="btn">Edit</a>
                    <a href="post/delete?id=<?= $post['id']; ?>" class="btn">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="<?= Common::getAssetPath('js/script.js') ?>"></script>
</body>