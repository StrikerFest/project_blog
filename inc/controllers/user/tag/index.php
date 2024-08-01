<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Tag;
use inc\models\Post;

// Import template của index
Common::requireTemplate('user/tag/index.php', [
    'tags' => Tag::getTags(),
]);

exit;