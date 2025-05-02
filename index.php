<?php

// تعریف مسیر اصلی پروژه
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

/**
 * فایل اصلی برنامه - نقطه ورود تمام درخواست‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// لود فایل router
require_once 'router.php';