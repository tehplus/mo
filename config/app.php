<?php
/**
 * تنظیمات پایه برنامه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// تنظیمات پایه
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/mo');
define('APP_NAME', 'سیستم حسابداری هوشمند');
define('APP_VERSION', '1.0.0');

// تنظیمات زمانی
date_default_timezone_set('Asia/Tehran');

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_NAME', 'mo');
define('DB_USER', 'root');
define('DB_PASS', '');

// تنظیمات برنامه
$config = [
    'app_name' => APP_NAME,
    'version' => APP_VERSION,
    'default_page' => 'home',
    'public_pages' => ['home', 'login', 'register'],
    'auth_pages' => ['login', 'register', 'forgot-password'],
    'admin_email' => 'admin@example.com',
    'support_email' => 'support@example.com',
    'per_page' => 10
];

// متغیرهای سراسری برای قالب
$meta = [
    'title' => APP_NAME,
    'description' => 'سیستم حسابداری آنلاین برای کسب و کارهای کوچک و متوسط',
    'keywords' => 'حسابداری، مالی، فاکتور، گزارش مالی'
];