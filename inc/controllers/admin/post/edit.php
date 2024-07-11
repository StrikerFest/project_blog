<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

Post::save_post();

Common::requireTemplate('admin/post/edit.php', [
    'post' => Post::getPostById($_GET['id'])
]);
