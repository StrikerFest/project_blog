<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

$posts = Post::getPosts();

// Import template của index
Common::requireTemplate('admin/post/index.php', [
    'posts' => $posts,
]);

exit;