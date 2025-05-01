<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// تنظیمات زمانی
date_default_timezone_set('Asia/Tehran');