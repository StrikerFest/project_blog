<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;

$customCssPath = $_SERVER['DOCUMENT_ROOT'] . Common::getAssetPath('css/custom.css');
$customAdminCssPath = $_SERVER['DOCUMENT_ROOT'] . Common::getAssetPath('css/custom-admin.css');

// Check if the request is POST (to handle saving the CSS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customCss = $_POST['customCss'] ?? '';
    $customAdminCss = $_POST['customAdminCss'] ?? '';

    // Save the updated CSS content to the respective files
    file_put_contents($customCssPath, $customCss);
    file_put_contents($customAdminCssPath, $customAdminCss);

    // Redirect back to the edit page with a success message
    header('Location: /admin/custom-css?success=1');
    exit();
}

// If not POST, fetch the custom CSS to display in the form
$customCss = Common::getCustomCss('custom.css');
$customAdminCss = Common::getCustomCss('custom-admin.css');

Common::requireTemplate('admin/custom-css/index.php', [
    'customCss' => $customCss,
    'customAdminCss' => $customAdminCss,
]);

exit;
