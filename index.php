<?php
/**
 * فایل اصلی برنامه - نقطه ورود تمام درخواست‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 09:56:10
 */

// شروع session
session_start();

// تعریف مسیر اصلی
define('BASE_PATH', __DIR__);

// لود فایل‌های اصلی به ترتیب اهمیت
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/classes/Database.php';
require_once BASE_PATH . '/includes/auth/functions.php';
require_once BASE_PATH . '/includes/auth/permissions.php';
require_once BASE_PATH . '/includes/classes/Router.php';

// ایجاد نمونه از کلاس Router
$router = new Router();

// مسیریابی به صفحه درخواستی
$router->route();