<?php

// Biến môi trường sử dụng trong dự án
// Không cần thay đổi
$_ENV['BASE_DIR'] = __DIR__;
$_ENV['AUTOLOAD'] = __DIR__ . '/Autoloader.php';
$_ENV['ASSETS_DIR'] = '/assets/';
$_ENV['UPLOAD_DIR'] = __DIR__ . '/assets/uploads/';
$_ENV['TEMPLATE_DIR'] = __DIR__ . '/templates';
$_ENV['CONTROLLER_DIR'] = __DIR__ . '/inc/controllers/';
$_ENV['SMTP_HOST'] = 'smtp.gmail.com';
$_ENV['SMTP_USERNAME'] = 'trinhtheanh789@gmail.com';
$_ENV['SMTP_PASSWORD'] = 'zlrwzlweljrqbwsp';
$_ENV['SMTP_PORT'] = '587';
$_ENV['DEFAULT_FROM_EMAIL'] = 'trinhtheanh789@gmail.com';
$_ENV['DEFAULT_FROM_NAME'] = 'Burogu';

// Các biến cơ sở dữ liệu sẽ ở đây
// Cần phải thay đổi tùy vào config của máy mỗi người
$_ENV['DB_HOST'] = 'localhost';
$_ENV['DB_USER'] = 'root';
$_ENV['DB_PASS'] = '';
$_ENV['DB_NAME'] = 'project_blog';
