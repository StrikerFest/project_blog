<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Tag;

$tags = Tag::getTags(true);

Common::requireTemplate('admin/tag/index.php', [
    'tags' => $tags,
]);

exit;