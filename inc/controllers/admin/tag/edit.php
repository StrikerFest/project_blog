<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Tag;

session_start(); // Start the session

Tag::save_tag();

$tag = null;

if (isset($_GET['id'])) {
    $tag = Tag::getTagById($_GET['id']);
}

// Check if there are any errors from the previous submission
$errors = $_SESSION['tag_errors'] ?? null;
unset($_SESSION['tag_errors']);

// Preserve form data in case of errors
$tagData = $_SESSION['tag_data'] ?? null;
unset($_SESSION['tag_data']);

Common::requireTemplate('admin/tag/edit.php', [
    'tag' => $tag,
    'errors' => $errors,
    'tagData' => $tagData
]);
