<?php
/**
 * توابع مدیریت صفحات
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 09:42:19
 */

/**
 * تبدیل نام صفحه به مسیر فایل
 */
function getPagePath($page) {
    // پاکسازی نام صفحه
    $page = trim(str_replace(['../', './'], '', $page));
    
    // لیست صفحات مجاز
    $allowed_pages = [
        'dashboard' => 'pages/dashboard/index.php',
        'categories' => 'pages/categories/index.php',
        'products' => 'pages/products/index.php',
        'customers' => 'pages/customers/index.php',
        'invoices' => 'pages/invoices/index.php',
        'settings' => 'pages/settings/index.php',
        'profile' => 'pages/profile/index.php',
        'error/403' => 'pages/error/403.php',
        'error/404' => 'pages/error/404.php',
        'error/500' => 'pages/error/500.php',
        'login' => 'pages/auth/login.php',
        'register' => 'pages/auth/register.php',
        'forgot-password' => 'pages/auth/forgot-password.php'
    ];
    
    // برگرداندن مسیر فایل
    return BASE_PATH . '/' . ($allowed_pages[$page] ?? 'pages/error/404.php');
}

/**
 * دریافت عنوان صفحه
 */
function getPageTitle($page) {
    // لیست عناوین صفحات
    $titles = [
        'dashboard' => 'داشبورد',
        'categories' => 'مدیریت دسته‌بندی‌ها',
        'products' => 'مدیریت محصولات',
        'customers' => 'مدیریت مشتریان',
        'invoices' => 'مدیریت فاکتورها',
        'settings' => 'تنظیمات',
        'profile' => 'پروفایل کاربری',
        'error/403' => 'دسترسی غیرمجاز',
        'error/404' => 'صفحه یافت نشد',
        'error/500' => 'خطای سرور',
        'login' => 'ورود به سیستم',
        'register' => 'ثبت نام',
        'forgot-password' => 'بازیابی رمز عبور'
    ];
    
    return $titles[$page] ?? 'صفحه نامشخص';
}