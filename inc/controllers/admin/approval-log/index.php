<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\ApprovalLog;

$approvalLogs = ApprovalLog::getAllApprovalLogs();

Common::requireTemplate('admin/approval-log/index.php', [
    'approvalLogs' => $approvalLogs,
]);
