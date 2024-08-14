<?php
require $_ENV['AUTOLOAD'];

use inc\models\Post;

if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
    Post::softDeletePost($_GET['id']);
} elseif ($_GET['action'] === 'recover' && isset($_GET['id'])) {
    Post::restorePost($_GET['id']);
}

header("Location: /admin/post");
exit;