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
    try {
        // اگر صفحه عمومی است
        if (in_array($page, $this->public_pages)) {
            return true;
        }

        // اگر کاربر لاگین نکرده
        if (!isset($_SESSION['user_id'])) {
            error_log(sprintf(
                "[%s] Access denied: No user session for page %s", 
                date('Y-m-d H:i:s'),
                $page
            ));
            return false;
        }

        // اگر کاربر ادمین است
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            error_log(sprintf(
                "[%s] Admin access granted for page %s", 
                date('Y-m-d H:i:s'),
                $page
            ));
            return true;
        }

        // بررسی دسترسی به صفحات خاص
        switch ($page) {
            case 'categories':
                if (!function_exists('checkUserPermission')) {
                    require_once BASE_PATH . '/includes/auth/permissions.php';
                }
                return checkUserPermission('manage_categories');
            
            case 'dashboard':
                return true;
                
            default:
                error_log(sprintf(
                    "[%s] Unknown page access check: %s for user %s", 
                    date('Y-m-d H:i:s'),
                    $page,
                    $_SESSION['username'] ?? 'unknown'
                ));
                return true;
        }
    } catch (Exception $e) {
        error_log(sprintf(
            "[%s] Error in checkAccess: %s", 
            date('Y-m-d H:i:s'),
            $e->getMessage()
        ));
        return false;
    }
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