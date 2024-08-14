<?php
require $_ENV['AUTOLOAD'];

use inc\models\Tag;

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'delete') {
        Tag::deleteTag($id);
    } elseif ($action === 'recover') {
        Tag::recoverTag($id);
    }
}

header("Location: /admin/tag");
exit;
