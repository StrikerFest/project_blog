<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

Common::requireTemplate('admin/layouts/headers.php', ['title' => 'Quản lý người dùng']);

$current_user = Common::getCurrentBackendUser();
$user = $args['user'] ?? null;

?>

<body>
<div class="edit-container">
    <?php if (isset($user['user_id'])): ?>
        <h1 class="edit-title">Cập nhật người dùng ID: <?= $user['user_id'] ?></h1>
    <?php else: ?>
        <h1 class="edit-title">Tạo người dùng mới</h1>
    <?php endif; ?>
    <form id="user-edit-createUserForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="<?= $user['user_id'] ?? '' ?>" name="user_id"/>

        <div class="edit-field">
            <label for="user-edit-username">Tên đăng nhập:</label>
            <input type="text" id="user-edit-username" name="username" value="<?= $user['username'] ?? '' ?>" placeholder="Tên đăng nhập" maxlength="50" required>
        </div>

        <div class="edit-field">
            <label for="user-edit-email">Email:</label>
            <input type="email" id="user-edit-email" name="email" value="<?= $user['email'] ?? '' ?>" placeholder="Email" maxlength="255" required>
        </div>

        <div class="edit-field">
            <label for="user-edit-role">Vai trò:</label>
            <select id="user-edit-role" name="role" required>
                <?php
                $roles = ['admin', 'author', 'editor', 'reader'];
                foreach ($roles as $role) {
                    $selected = ($role === ($user['role'] ?? 'reader')) ? 'selected' : '';
                    echo "<option value='$role' $selected>$role</option>";
                }
                ?>
            </select>
        </div>

        <div class="edit-field">
            <label for="user-edit-bio">Giới thiệu:</label>
            <textarea id="user-edit-bio" name="bio" placeholder="Giới thiệu"><?= $user['bio'] ?? '' ?></textarea>
        </div>

        <div class="edit-field">
            <label for="user-edit-profile-image">Ảnh đại diện:</label>
            <input type="file" id="user-edit-profile-image" name="profile_image" accept="image/*">
            <img id="profile-image-preview" src="<?= htmlspecialchars($user['profile_image'] ?? Common::getAssetPath('images/avatar.webp')) ?>" alt="Xem trước ảnh đại diện" style="margin-top: 10px; max-width: 200px; display: block;">
        </div>

        <div id="password-fields">
            <div class="edit-field">
                <label for="user-edit-password">Mật khẩu:</label>
                <input type="password" id="user-edit-password" name="password" placeholder="Mật khẩu" <?= isset($user['user_id']) ? '' : 'required' ?>>
            </div>

            <div class="edit-field">
                <label for="user-edit-confirm-password">Xác nhận mật khẩu:</label>
                <input type="password" id="user-edit-confirm-password" name="confirm_password" placeholder="Xác nhận mật khẩu">
            </div>

            <ul id="password-requirements" class="edit-error" style="display:none;"></ul>
            <span id="password-error" class="edit-error" style="display:none;"></span>
        </div>

        <div class="edit-field">
            <button type="submit" class="edit-btn">Lưu người dùng</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const profileImageInput = $('#user-edit-profile-image');
        const profileImagePreview = $('#profile-image-preview');

        profileImageInput.change(function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImagePreview.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        const passwordInput = $('#user-edit-password');
        const confirmPasswordInput = $('#user-edit-confirm-password');
        const passwordRequirements = $('#password-requirements');
        const passwordError = $('#password-error');

        passwordInput.on('input', function () {
            const password = passwordInput.val();
            const requirements = [];

            if (password.length < 8) {
                requirements.push('Yêu cầu tối thiểu 8 ký tự');
            }

            if (!/[A-Z]/.test(password)) {
                requirements.push('Yêu cầu ít nhất một chữ cái viết hoa');
            }

            if (!/[0-9!@#$%^&*(),.?":{}|<>]/.test(password)) {
                requirements.push('Yêu cầu một số hoặc ký tự đặc biệt');
            }

            if (requirements.length > 0) {
                passwordRequirements.html(requirements.map(req => `<li>${req}</li>`).join('')).show();
            } else {
                passwordRequirements.hide();
            }
        });

        $('#user-edit-createUserForm').submit(function (e) {
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
</body>
