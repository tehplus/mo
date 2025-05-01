<?php
/**
 * تنظیمات اصلی برنامه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// تنظیمات نمایش خطاها (در محیط توسعه)
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