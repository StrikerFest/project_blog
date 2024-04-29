<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

User::logout(2);

Common::requireTemplate('admin/login.php', []);

exit;