<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

$filters = [
    'id' => $_GET['id'] ?? '',
    'title' => $_GET['title'] ?? '',
    'categories' => $_GET['categories'] ?? '',
    'tags' => $_GET['tags'] ?? '',
    'author_name' => $_GET['author'] ?? '',
    'editor_name' => $_GET['editor'] ?? '',
    'status' => $_GET['status'] ?? '',
    'published_at' => $_GET['publish_date'] ?? '',
];

$includeDeleted = isset($_GET['include_deleted']);

$posts = Post::getFilteredPosts($filters, $includeDeleted);

Common::requireTemplate('admin/post/index.php', [
    'posts' => $posts,
]);

exit;
