<?php
/**
 * هدر اصلی برنامه
 * شامل: تگ‌های HTML ضروری، متاتگ‌ها، لینک‌های CSS و فونت‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

// اگر session شروع نشده باشه، شروع می‌کنیم
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once BASE_PATH . '/includes/auth/permissions.php';
// اگر ثابت‌های مورد نیاز تعریف نشده باشند، تعریف می‌کنیم
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/mo');
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="سیستم مدیریت هوشمند">
    <meta name="author" content="tehplus">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . APP_NAME : APP_NAME; ?></title>

    <!-- فونت‌های ایران‌سنس -->
    <link href="<?php echo BASE_URL; ?>/assets/fonts/iranSans/css/fontiran.css" rel="stylesheet">
    
    <!-- استایل‌های اصلی -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">


    <!-- استایل‌های اختصاصی -->
    <link href="<?php echo BASE_URL; ?>/assets/css/main.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/css/sidebar.css" rel="stylesheet">
    <?php if (isset($page_css)): ?>
        <link href="<?php echo BASE_URL; ?>/assets/css/<?php echo $page_css; ?>.css" rel="stylesheet">
    
        <?php endif; ?>
    
    <!-- اسکریپت‌های اصلی -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

</head>
<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">
    <!-- شروع محتوای اصلی -->
    <div class="wrapper">
        <?php 
        // اضافه کردن سایدبار
        require_once BASE_PATH . '/includes/sidebar.php';
        ?>
        
        <!-- شروع محتوای اصلی -->
        <div class="content-wrapper">