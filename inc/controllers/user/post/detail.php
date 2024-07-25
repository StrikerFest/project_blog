<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

$posts = Post::getPosts();

// Import template của index
Common::requireTemplate('user/post/detail.php', [
    'post' => Post::getPostById($_GET['id'] ?? null)
]);

exit;