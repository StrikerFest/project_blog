<?php
require $_ENV['AUTOLOAD'];

use inc\models\Post;

if(isset($_GET['id'])){
    Post::deletePost($_GET['id']);
}

header("Location: /admin/post");
exit;