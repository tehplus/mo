<?php
/**
 * تنظیمات اصلی برنامه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 10:35:19
 */

// تنظیمات خطایابی (فقط در محیط توسعه)
if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// بررسی اینکه session شروع نشده باشد
if (session_status() === PHP_SESSION_NONE) {
    // تنظیمات session قبل از شروع آن
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // در محیط production باید 1 باشد
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 0);
    
    // تنظیم نام session
    session_name('MO_SESSION');
    
    // شروع session
    session_start();
}

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_NAME', 'mo');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_persian_ci');

// تنظیمات برنامه
define('APP_NAME', 'سیستم مدیریت');
define('APP_VERSION', '1.0.0');
define('APP_RELEASE_DATE', '2025-05-02');

// تنظیمات مسیرها
define('BASE_URL', 'http://localhost/mo');
define('BASE_PATH', realpath(__DIR__ . '/..'));
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('CLASSES_PATH', INCLUDES_PATH . '/classes');
define('AUTH_PATH', INCLUDES_PATH . '/auth');
define('TEMPLATES_PATH', INCLUDES_PATH . '/templates');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// تنظیمات زمانی
define('TIMEZONE', 'Asia/Tehran');
date_default_timezone_set(TIMEZONE);

// تنظیمات امنیتی
define('SECURE_COOKIE', false); // در محیط production باید true باشد
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('CSRF_TOKEN_NAME', 'mo_csrf_token');

// توابع کمکی عمومی
if (!function_exists('is_ajax')) {
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

if (!function_exists('redirect')) {
    function redirect($to, $with = []) {
        if (!empty($with)) {
            foreach ($with as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        header('Location: ' . BASE_URL . $to);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && 
               hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
}

// لود کردن کلاس‌های اصلی
spl_autoload_register(function ($class) {
    $file = CLASSES_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// کلاس‌های مورد نیاز
require_once AUTH_PATH . '/functions.php';
require_once AUTH_PATH . '/permissions.php';