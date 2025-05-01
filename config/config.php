<?php
// تنظیمات پایه
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/mo');

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_NAME', 'mo');
define('DB_USER', 'root');
define('DB_PASS', '');

// تنظیمات برنامه
$config = [
    'default_page' => 'dashboard',
    'auth_pages' => ['login', 'register', 'forgot-password'],
    'require_auth' => true
];