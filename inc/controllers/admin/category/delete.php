<?php
require $_ENV['AUTOLOAD'];

use inc\models\Category;

if(isset($_GET['id'])){
    Category::deleteCategory($_GET['id']);
}

header("Location: /admin/category");
exit;