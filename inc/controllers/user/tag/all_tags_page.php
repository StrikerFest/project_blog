<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;

/**
 * @var $route_args
 */

// Import template của index
Common::requireTemplate('user/tag/all_tags_page.php', [ 'route_args' => $route_args ?? [] ]);

exit;