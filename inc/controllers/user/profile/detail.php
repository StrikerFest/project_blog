<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

session_start();

// Update user information if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updateSuccess = User::updateUserProfile();

    if ($updateSuccess) {
        $_SESSION['success_update'] = 'User information updated successfully.';
    } else {
        $_SESSION['error_update'] = $_SESSION['error_update'] ?? 'Failed to update user information. Please try again.';
    }

    // Redirect to the profile page to prevent form resubmission
    header("Location: " . Common::get_url('profile'));
    exit();
}

// Get the updated user information from the session
$user_information = Common::getFrontendUser();

// Import the template
Common::requireTemplate('user/profile/detail.php', [
    'user_information' => $user_information,
]);

exit;
