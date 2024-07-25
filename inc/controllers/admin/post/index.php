<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

// Import template của index
Common::requireTemplate('admin/post/index.php', [
    'posts' => Post::getPosts('desc', false),
]);

exit;