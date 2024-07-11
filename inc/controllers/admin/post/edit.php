<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;
use inc\models\Tag;

Post::save_post();

Common::requireTemplate('admin/post/edit.php', [
    'post' => Post::getPostById($_GET['id'] ?? null),
    'categories' => Category::getCategories(),
    'tags' => Tag::getTags()
]);
