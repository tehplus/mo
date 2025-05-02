<?php

// تعریف مسیرهای اصلی
define('BASE_PATH', __DIR__);
define('INCLUDE_PATH', BASE_PATH . '/includes');
define('CLASS_PATH', INCLUDE_PATH . '/classes');
define('AUTH_PATH', INCLUDE_PATH . '/auth');
define('PAGE_PATH', BASE_PATH . '/pages');

/**
 * فایل اصلی برنامه - نقطه ورود تمام درخواست‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// لود فایل router
require_once 'router.php';