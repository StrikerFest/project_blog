<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

$user = $args['user'];

Common::requireTemplate('admin/layouts/headers.php', ['title' => 'Chỉnh sửa Hồ sơ']);

?>

<div class="edit-container">
    <h1 class="edit-title">Chỉnh sửa Hồ sơ</h1>
    <form id="admin-profile-edit-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="<?= $user['user_id'] ?>" name="user_id"/>

        <div class="edit-field">
            <label for="admin-edit-username">Tên người dùng:</label>
            <input type="text" id="admin-edit-username" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Tên người dùng" maxlength="50" required>
        </div>

        <div class="edit-field">
            <label for="admin-edit-email">Email:</label>
            <input type="email" id="admin-edit-email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Email" maxlength="255" required>
        </div>

        <div class="edit-field">
            <label for="admin-edit-bio">Tiểu sử:</label>
            <textarea id="admin-edit-bio" name="bio" placeholder="Tiểu sử"><?= htmlspecialchars($user['bio']) ?></textarea>
        </div>

        <div class="edit-field">
            <label for="admin-edit-profile-image">Hình ảnh hồ sơ:</label>
            <input type="file" id="admin-edit-profile-image" name="profile_image" accept="image/*">
            <img id="profile-image-preview" src="<?= htmlspecialchars($user['profile_image'] ?? Common::getAssetPath('images/avatar.webp')) ?>" alt="Xem trước hình ảnh hồ sơ" style="margin-top: 10px; max-width: 200px; display: block;">
        </div>

        <div class="edit-field">
            <label for="admin-edit-old-password">Mật khẩu cũ:</label>
            <input type="password" id="admin-edit-old-password" name="old_password" placeholder="Mật khẩu cũ">
        </div>

        <div id="new-password-fields" style="display: none;">
            <div class="edit-field">
                <label for="admin-edit-password">Mật khẩu mới:</label>
                <input type="password" id="admin-edit-password" name="password" placeholder="Mật khẩu mới">
            </div>

            <div class="edit-field">
                <label for="admin-edit-confirm-password">Xác nhận mật khẩu:</label>
                <input type="password" id="admin-edit-confirm-password" name="confirm_password" placeholder="Xác nhận mật khẩu">
            </div>

            <ul id="password-requirements" class="edit-error" style="display:none;"></ul>
            <span id="password-error" class="edit-error" style="display:none;"></span>
        </div>

        <div class="edit-field">
            <button type="submit" class="edit-btn">Lưu Hồ sơ</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const oldPasswordInput = $('#admin-edit-old-password');
        const newPasswordFields = $('#new-password-fields');
        const passwordInput = $('#admin-edit-password');
        const confirmPasswordInput = $('#admin-edit-confirm-password');
        const passwordRequirements = $('#password-requirements');
        const passwordError = $('#password-error');

        oldPasswordInput.on('input', function () {
            if ($(this).val()) {
                newPasswordFields.show();
            } else {
                newPasswordFields.hide();
            }
        });

        passwordInput.on('input', function () {
            const password = passwordInput.val();
            const requirements = [];

            if (password.length < 8) {
                requirements.push('Yêu cầu ít nhất 8 ký tự');
            }

            if (!/[A-Z]/.test(password)) {
                requirements.push('Yêu cầu ít nhất một ký tự viết hoa');
            }

            if (!/[0-9!@#$%^&*(),.?":{}|<>]/.test(password)) {
                requirements.push('Yêu cầu có số hoặc ký tự đặc biệt');
            }

            if (requirements.length > 0) {
                passwordRequirements.html(requirements.map(req => `<li>${req}</li>`).join('')).show();
            } else {
                passwordRequirements.hide();
            }
        });

        $('#admin-profile-edit-form').submit(function (e) {
            const password = passwordInput.val();
            const confirmPassword = confirmPasswordInput.val();

            if (password && password !== confirmPassword) {
                e.preventDefault();
                passwordError.text('Mật khẩu không khớp.').show();
            } else {
                passwordError.hide();
            }
        });
    });
</script>

<?php
Common::requireTemplate('admin/layouts/footer.php');
?>
