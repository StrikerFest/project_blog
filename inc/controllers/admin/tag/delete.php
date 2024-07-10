<?php
require $_ENV['AUTOLOAD'];

use inc\models\Tag;

if(isset($_GET['id'])){
    Tag::deleteTag($_GET['id']);
}

header("Location: /admin/tag");
exit;