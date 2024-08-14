<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;

session_start();

Category::save_category();

$category = null;

if (isset($_GET['id'])) {
    $category = Category::getCategoryById($_GET['id']);
}

// Check if there are any errors from the previous submission
$errors = $_SESSION['category_errors'] ?? null;
unset($_SESSION['category_errors']);

// Preserve form data in case of errors
$categoryData = $_SESSION['category_data'] ?? null;
unset($_SESSION['category_data']);

Common::requireTemplate('admin/category/edit.php', [
    'category' => $category,
    'errors' => $errors,
    'categoryData' => $categoryData
]);
