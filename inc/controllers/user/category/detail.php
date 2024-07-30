<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;

/**
 * @var $route_args
 */

// Import template của index
Common::requireTemplate('user/category/detail.php', [ 'route_args' => $route_args ?? [] ]);

exit;