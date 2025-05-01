<?php
/**
 * مسیریاب اصلی برنامه
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

session_start();
date_default_timezone_set('Asia/Tehran');

// تنظیمات پایه
define('BASE_PATH', __DIR__);
define('BASE_URL', '/mo');
define('APP_NAME', 'سیستم حسابداری هوشمند');

// لیست صفحات مجاز برای دسترسی عمومی
$public_pages = [
    'home',    // صفحه اصلی لندینگ
    'login',   // ورود
    'register' // ثبت نام
];

// دریافت صفحه درخواستی
$page = isset($_GET['page']) ? strtolower(trim($_GET['page'])) : 'home';

// حذف کاراکترهای غیرمجاز از نام صفحه
$page = preg_replace('/[^a-z0-9\-_]/', '', $page);

// اگر صفحه خالی شد
if (empty($page)) {
    $page = 'home';
}

// اگر صفحه عمومی نیست و کاربر لاگین نکرده
if (!in_array($page, $public_pages) && !isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/?page=login');
    exit;
}

// بررسی نوع صفحه (عمومی یا داشبورد)
$is_public_page = in_array($page, $public_pages);

// تنظیم متغیرهای قالب
$meta = [
    'title' => APP_NAME,
    'description' => 'سیستم حسابداری آنلاین برای کسب و کارهای کوچک و متوسط',
];

// انتخاب قالب مناسب
if ($is_public_page) {
    switch ($page) {
        case 'home':
            require_once 'pages/home.php';
            exit;
            
        case 'login':
        case 'register':
            // اگر کاربر لاگین کرده بود
            if (isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/?page=dashboard');
                exit;
            }
            require_once "pages/auth/{$page}.php";
            exit;
    }
} else {
    // لود قالب پنل مدیریت
    include_once 'includes/header.php';
    include_once 'includes/sidebar.php';
    
    echo '<div class="content-wrapper">';
    
    // لود صفحه درخواستی
    $page_path = "pages/{$page}/index.php";
    if (file_exists($page_path)) {
        require_once $page_path;
    } else {
        // صفحه 404
        echo '<div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">صفحه مورد نظر یافت نشد</h1>
                        </div>
                    </div>
                </div>
            </div>';
    }
    
    echo '</div><!-- /.content-wrapper -->';
    
    include_once 'includes/footer.php';
}