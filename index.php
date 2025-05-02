<?php
/**
 * فایل اصلی پروژه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

// تنظیم محیط (development یا production)
define('DEVELOPMENT_MODE', true);

// لود کردن تنظیمات
require_once __DIR__ . '/includes/config.php';

try {
    // ایجاد نمونه از کلاس Router
    $router = new Router();
    
    // اجرای مسیریابی
    $router->route();
    
} catch (Exception $e) {
    // لاگ خطا
    error_log(sprintf(
        "[%s] Error in index.php: %s", 
        date('Y-m-d H:i:s'), 
        $e->getMessage()
    ));
    
    // نمایش خطا به کاربر
    if (DEVELOPMENT_MODE) {
        echo '<pre>';
        echo "Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
        echo "Trace:\n" . $e->getTraceAsString();
        echo '</pre>';
    } else {
        redirect('/?page=error');
    }
}