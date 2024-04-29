<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

User::logout(1);

Common::requireTemplate('admin/login.php', []);

exit;