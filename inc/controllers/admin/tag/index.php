<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Tag;

$filters = [
    'id' => $_GET['id'] ?? '',
    'name' => $_GET['name'] ?? '',
    'status' => $_GET['status'] ?? '',
    'position' => $_GET['position'] ?? ''
];

$includeDeleted = isset($_GET['include_deleted']);

$tags = Tag::getFilteredTags($filters, $includeDeleted);

Common::requireTemplate('admin/tag/index.php', [
    'tags' => $tags,
]);

exit;
