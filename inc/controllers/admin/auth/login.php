<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

User::login(User::ROLE_ADMIN);

Common::requireTemplate('admin/login.php', []);

exit;
