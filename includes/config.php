<?php
/**
 * تنظیمات اصلی برنامه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// نمایش خطاها در محیط توسعه
error_reporting(E_ALL);
ini_set('display_errors', 1);

// تنظیمات پایگاه داده
define('DB_HOST', 'localhost');     // آدرس سرور دیتابیس
define('DB_NAME', 'mo');            // نام دیتابیس
define('DB_USER', 'root');          // نام کاربری دیتابیس
define('DB_PASS', '');              // رمز عبور دیتابیس

// تنظیمات زمانی
date_default_timezone_set('Asia/Tehran');

// تنظیمات امنیتی
define('SITE_KEY', 'mo-' . date('Y')); // کلید امنیتی سایت

// تنظیمات مسیرها
define('BASE_PATH', dirname(__DIR__)); // مسیر اصلی پروژه
define('BASE_URL', '/mo');            // آدرس پایه وب‌سایت
define('UPLOADS_PATH', BASE_PATH . '/uploads');  // مسیر آپلود فایل‌ها

// نام و نسخه برنامه
define('APP_NAME', 'سیستم حسابداری هوشمند');
define('APP_VERSION', '1.0.0');

// تنظیمات ایمیل (اختیاری)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-password');
define('MAIL_FROM_ADDRESS', 'your-email@gmail.com');
define('MAIL_FROM_NAME', APP_NAME);