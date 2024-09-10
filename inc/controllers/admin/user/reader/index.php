<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

// Import template for the user index
Common::requireTemplate('admin/user/reader/index.php', [
    'users' => User::getUsers(false, ['reader'],'DESC'),
]);

exit;
