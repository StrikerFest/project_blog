<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

User::register(User::ROLE_USER);

Common::requireTemplate('user/auth/register.php', []);

exit;