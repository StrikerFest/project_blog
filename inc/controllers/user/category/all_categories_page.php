<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;

/**
 * @var $route_args
 */

// Import template của index
Common::requireTemplate('user/category/all_categories_page.php', [ 'route_args' => $route_args ?? [] ]);

exit;