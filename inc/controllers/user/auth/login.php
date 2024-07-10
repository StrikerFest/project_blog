<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

User::login(2);

Common::requireTemplate('user/login.php', []);

exit;