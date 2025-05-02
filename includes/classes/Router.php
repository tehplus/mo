<?php
/**
 * کلاس مدیریت مسیریابی
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 10:04:27
 */

class Router 
{
    /**
     * @var array صفحات عمومی که نیاز به لاگین ندارند
     */
    private $public_pages = ['login', 'register', 'forgot-password'];

    /**
     * @var array قالب‌های پیش‌فرض برای هر نوع صفحه
     */
    private $layouts = [
        'public' => [
            'header' => 'includes/template/auth/header.php',
            'footer' => 'includes/template/auth/footer.php'
        ],
        'dashboard' => [
            'header' => 'includes/template/header.php',
            'sidebar' => 'includes/template/sidebar.php',
            'footer' => 'includes/template/footer.php'
        ],
        'error' => [
            'header' => 'includes/template/error/header.php',
            'footer' => 'includes/template/error/footer.php'
        ]
    ];

    /**
     * مسیریابی به صفحه درخواستی
     */
    public function route() 
    {
        try {
            if (isset($_GET['page']) && $_GET['page'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/includes/auth/login_handler.php';
            return;
                }
            // دریافت صفحه درخواستی
            $page = isset($_GET['page']) ? $this->cleanPageName($_GET['page']) : 'dashboard';
            
            // بررسی دسترسی عمومی
            $is_public = in_array($page, $this->public_pages);
            
            // اگر کاربر لاگین نکرده و صفحه عمومی نیست
            if (!$is_public && !isset($_SESSION['user_id'])) {
                $this->redirect('login');
            }

            // پیدا کردن مسیر فایل صفحه
            $page_file = $this->getPageFile($page);
            
            // اگر فایل وجود نداشت
            if (!$page_file) {
                $this->showError('404');
                return;
            }

            // بررسی دسترسی به صفحه
            if (!$this->checkAccess($page)) {
                $this->showError('403');
                return;
            }

            // نمایش صفحه
            $this->renderPage($page, $page_file, $is_public ? 'public' : 'dashboard');

        } catch (Exception $e) {
            // لاگ خطا
            error_log($e->getMessage());
            $this->showError('500');
        }
    }

    /**
     * پاکسازی نام صفحه
     */
    private function cleanPageName($page) 
    {
        return preg_replace('/[^a-z0-9\-_]/', '', strtolower(trim($page)));
    }

    /**
     * پیدا کردن مسیر فایل صفحه
     */
    private function getPageFile($page) 
    {
        // مسیرهای ممکن برای فایل
        $possible_paths = [
            "pages/{$page}.php",
            "pages/{$page}/index.php",
            "pages/auth/{$page}.php"
        ];

        // بررسی وجود فایل در مسیرهای ممکن
        foreach ($possible_paths as $path) {
            if (file_exists(BASE_PATH . '/' . $path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * بررسی دسترسی به صفحه
     */
    private function checkAccess($page) 
    {
        // اگر صفحه عمومی است
        if (in_array($page, $this->public_pages)) {
            return true;
        }

        // اگر کاربر لاگین نکرده
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // اگر کاربر ادمین است
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            return true;
        }

        // بررسی دسترسی صفحه categories
        if ($page === 'categories') {
            return checkUserPermission('manage_categories');
        }

        // برای سایر صفحات داشبورد
        return true;
    }

    /**
     * نمایش صفحه
     */
    private function renderPage($page, $page_file, $layout = 'dashboard') 
    {
        // تنظیم متغیرهای سراسری مورد نیاز صفحه
        global $page_title, $page_css;
        $page_title = ucfirst($page);
        $page_css = $page;

        // لود کردن هدر
        if (file_exists(BASE_PATH . '/' . $this->layouts[$layout]['header'])) {
            require_once BASE_PATH . '/' . $this->layouts[$layout]['header'];
        }

        // لود کردن سایدبار برای داشبورد
        if ($layout === 'dashboard' && file_exists(BASE_PATH . '/' . $this->layouts[$layout]['sidebar'])) {
            require_once BASE_PATH . '/' . $this->layouts[$layout]['sidebar'];
            echo '<div class="content-wrapper">';
        }

        // لود کردن محتوای اصلی
        require_once BASE_PATH . '/' . $page_file;

        // بستن content-wrapper
        if ($layout === 'dashboard') {
            echo '</div>';
        }

        // لود کردن فوتر
        if (file_exists(BASE_PATH . '/' . $this->layouts[$layout]['footer'])) {
            require_once BASE_PATH . '/' . $this->layouts[$layout]['footer'];
        }
    }

    /**
     * نمایش صفحه خطا
     */
    private function showError($error) 
    {
        $error_file = "pages/error/{$error}.php";
        if (file_exists(BASE_PATH . '/' . $error_file)) {
            header("HTTP/1.1 {$error} Error");
            $this->renderPage($error, $error_file, 'error');
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<h1>خطای سیستم: {$error}</h1>";
            echo "<p>فایل خطای مورد نظر یافت نشد.</p>";
        }
    }
    /**
     * ریدایرکت به صفحه دیگر
     */
    private function redirect($page) 
    {
        header("Location: " . BASE_URL . "/?page={$page}");
        exit;
    }
}