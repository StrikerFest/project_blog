<?php

/**
 * @var $args
 */
use inc\helpers\Common;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_information = $args['user_information'];

Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Sign Up'
]);
?>

<link rel="stylesheet" href="<?= Common::getAssetPath('css/profile-user.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="profile-container">
    <h2 class="profile-title">User Profile</h2>
    <?php if (isset($_SESSION['success_update'])): ?>
        <div class="profile-success"><?= $_SESSION['success_update'] ?></div>
        <?php unset($_SESSION['success_update']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_update'])): ?>
        <div class="profile-error"><?= $_SESSION['error_update'] ?></div>
        <?php unset($_SESSION['error_update']); ?>
    <?php endif; ?>
    <form id="profile-form" class="profile-form" method="POST" enctype="multipart/form-data">
        <div class="profile-content">
            <div class="profile-field">
                <label for="profile-picture">Profile Picture:</label>
                <img src="<?= $user_information['profile_picture'] ?>" alt="Profile Picture" class="profile-picture-display" id="profile-picture-display">
                <input type="file" id="profile-picture" name="profile_picture" class="profile-input" style="display: none;">
                <button type="button" id="change-picture-button" class="profile-btn-small">Change Picture</button>
            </div>
            <div class="profile-field">
                <label for="username">Username:</label>
                <input class="profile-input" type="text" id="username" name="username" value="<?= $user_information['username'] ?>" readonly />
            </div>
            <div class="profile-field">
                <label for="email">Email:</label>
                <input class="profile-input" type="email" id="email" name="email" value="<?= $user_information['email'] ?>" readonly />
            </div>
            <div class="profile-field">
                <label for="bio">Bio:</label>
                <textarea class="profile-input" id="bio" name="bio" rows="4" readonly><?= $user_information['bio'] ?></textarea>
            </div>
            <div class="profile-field">
                <label for="created_at">Created At:</label>
                <input class="profile-input" type="text" id="created_at" value="<?= $user_information['created_at'] ?>" readonly />
            </div>
            <div class="profile-field">
                <label for="updated_at">Updated At:</label>
                <input class="profile-input" type="text" id="updated_at" value="<?= $user_information['updated_at'] ?>" readonly />
            </div>
            <div id="password-fields" style="display:none;">
                <div class="profile-field">
                    <label for="old-password">Old Password:</label>
                    <input class="profile-input" type="password" id="old-password" name="old_password" />
                </div>
                <div class="profile-field">
                    <label for="password">New Password:</label>
                    <input class="profile-input" type="password" id="password" name="password" />
                </div>
                <div class="profile-field">
                    <label for="confirm-password">Confirm Password:</label>
                    <input class="profile-input" type="password" id="confirm-password" name="confirm_password" />
                </div>
                <ul id="password-requirements" class="profile-error" style="display:none;"></ul>
                <span id="password-error" class="profile-error" style="display:none;"></span>
                <span id="old-password-error" class="profile-error" style="display:none;">Old password entered without new password.</span>
            </div>
        </div>
        <div class="profile-buttons">
            <button type="button" id="edit-button" class="profile-btn">Edit</button>
            <button type="submit" id="save-button" class="profile-btn" style="display:none;">Save</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const editButton = $('#edit-button');
        const saveButton = $('#save-button');
        const passwordFields = $('#password-fields');
        const passwordRequirements = $('#password-requirements');
        const oldPasswordInput = $('#old-password');
        const passwordInput = $('#password');
        const confirmPasswordInput = $('#confirm-password');
        const passwordError = $('#password-error');
        const oldPasswordError = $('#old-password-error');
        const profilePictureInput = $('#profile-picture');
        const profilePictureDisplay = $('#profile-picture-display');
        const changePictureButton = $('#change-picture-button');

        const originalValues = {
            username: $('#username').val(),
            email: $('#email').val(),
            bio: $('#bio').val(),
            profile_picture: profilePictureDisplay.attr('src'),
        };

        editButton.click(function () {
            if (editButton.text() === 'Edit') {
                $('#username, #email, #bio').prop('readonly', false);
                passwordFields.show();
                changePictureButton.show();
                editButton.text('Cancel');
                saveButton.show();
            } else {
                $('#username').val(originalValues.username);
                $('#email').val(originalValues.email);
                $('#bio').val(originalValues.bio);
                profilePictureDisplay.attr('src', originalValues.profile_picture);
                $('#username, #email, #bio').prop('readonly', true);
                passwordFields.hide();
                changePictureButton.hide();
                editButton.text('Edit');
                saveButton.hide();
                oldPasswordInput.val('');
                passwordInput.val('');
                confirmPasswordInput.val('');
                passwordRequirements.hide();
                passwordError.hide();
                oldPasswordError.hide();
            }
        });

        changePictureButton.click(function () {
            profilePictureInput.click();
        });

        profilePictureInput.change(function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePictureDisplay.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        passwordInput.on('input', function () {
            const password = passwordInput.val();
            const requirements = [];

            if (password.length < 8) {
                requirements.push('Require at least 8 characters');
            }

            if (!/[A-Z]/.test(password)) {
                requirements.push('Require at least one uppercase letter');
            }

            if (!/[0-9!@#$%^&*(),.?":{}|<>]/.test(password)) {
                requirements.push('Require a number or a special character');
            }

            if (requirements.length > 0) {
                passwordRequirements.html(requirements.map(req => `<li>${req}</li>`).join('')).show();
            } else {
                passwordRequirements.hide();
            }
        });

        $('#profile-form').submit(function (e) {
            const oldPassword = oldPasswordInput.val();
            const password = passwordInput.val();
            const confirmPassword = confirmPasswordInput.val();

            if (oldPassword && !password) {
                e.preventDefault();
                oldPasswordError.show();
            } else {
                oldPasswordError.hide();
            }

            if (password && password !== confirmPassword) {
                e.preventDefault();
                passwordError.text('Passwords do not match.').show();
            } else {
                passwordError.hide();
            }
        });
    });
</script>
</body>
