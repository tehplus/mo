<?php
/**
 * فایل اصلی برنامه - نقطه ورود تمام درخواست‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 05:15:43
 */

session_start(); // باید اینجا باشه قبل از هر چیزی
define('BASE_PATH', __DIR__); // همینجا تعریف کنید

// لود فایل router
require_once 'router.php';