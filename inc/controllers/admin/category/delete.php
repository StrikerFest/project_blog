<?php
require $_ENV['AUTOLOAD'];

use inc\models\Category;

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'delete') {
        Category::deleteCategory($id);
    } elseif ($action === 'recover') {
        Category::recoverCategory($id);
    }
}

header("Location: /admin/category");
exit;
