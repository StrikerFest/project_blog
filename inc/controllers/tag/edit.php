<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Tag;

Tag::save_tag();

$tag = null;

if(isset($_GET['id'])){
    $tag = Tag::getTagById($_GET['id']);
}

Common::requireTemplate('admin/tag/edit.php', [
    'tag' => $tag
]);
