<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

Post::save_post();

$post = null;

// Kiểm tra xem có mã post được truyền vào không
// Nếu có thì sẽ lấy thông tin của POST
if(isset($_GET['id'])){
    $post = Post::getPostById($_GET['id']);
}

Common::requireTemplate('admin/post/edit.php', [
    'post' => $post
]);
