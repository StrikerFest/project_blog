<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

$posts = Post::getPosts('asc', false, true);

Common::requireTemplate('admin/post/index.php', [
    'posts' => $posts,
]);

exit;
