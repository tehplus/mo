<?php
/**
 * کلاس اصلی مسیریابی
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

class Router {
    private $page;
    private $action;
    private $template = 'default';
    private $public_pages = ['login', 'register', 'forgot-password', 'reset-password'];
    
    public function __construct() {
        $this->page = $_GET['page'] ?? 'dashboard';
        $this->action = $_GET['action'] ?? 'index';
    }
    
    /**
     * مسیریابی درخواست‌ها
     */
    public function route() {
        try {
            // بررسی درخواست login
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->page === 'login') {
                // بررسی درخواست AJAX
                if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
                    $this->jsonResponse(403, 'درخواست غیرمجاز');
                }
                
                require_once BASE_PATH . '/includes/auth/login_handler.php';
                exit;
            }

            // بررسی دسترسی به صفحه
            if (!$this->checkAccess($this->page)) {
                $this->redirect('login');
            }

            // مسیر فایل صفحه
            $page_file = $this->getPageFile();
            
            if (!file_exists($page_file)) {
                throw new Exception('صفحه مورد نظر یافت نشد');
            }

            // تنظیم قالب براساس صفحه
            $this->setTemplate();
            
            // نمایش صفحه
            $this->renderPage();

        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * بررسی دسترسی کاربر به صفحه
     */
    private function checkAccess($page) {
        // صفحات عمومی
        if (in_array($page, $this->public_pages)) {
            return true;
        }

        // بررسی لاگین
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // بررسی دسترسی‌های خاص
        switch ($page) {
            case 'categories':
                return checkUserPermission('manage_categories');
            case 'users':
                return checkUserPermission('manage_users');
            default:
                return true;
        }
    }

    /**
     * دریافت مسیر فایل صفحه
     */
    private function getPageFile() {
        $base = BASE_PATH . '/pages/';
        
        // مسیرهای خاص
        switch ($this->page) {
            case 'login':
            case 'register':
            case 'forgot-password':
            case 'reset-password':
                return $base . 'auth/' . $this->page . '.php';
            
            case 'error':
                $code = $_GET['code'] ?? '404';
                return $base . 'error/' . $code . '.php';
        }

        // صفحات عادی
        if ($this->action === 'index') {
            return $base . $this->page . '/index.php';
        }

        return $base . $this->page . '/' . $this->action . '.php';
    }

    /**
     * تنظیم قالب براساس صفحه
     */
    private function setTemplate() {
        switch ($this->page) {
            case 'login':
            case 'register':
            case 'forgot-password':
            case 'reset-password':
                $this->template = 'auth';
                break;
            
            case 'error':
                $this->template = 'error';
                break;
        }
    }

    /**
     * نمایش صفحه با قالب مناسب
     */
    private function renderPage() {
        // متغیرهای پیش‌فرض قالب
        $meta = [
            'title' => APP_NAME,
            'description' => ''
        ];

        // لود هدر قالب
        require_once BASE_PATH . '/includes/template/' . $this->template . '/header.php';
        
        // لود محتوای صفحه
        require_once $this->getPageFile();
        
        // لود فوتر قالب
        require_once BASE_PATH . '/includes/template/' . $this->template . '/footer.php';
    }

    /**
     * مدیریت خطاها
     */
    private function handleError($error) {
        if (is_ajax()) {
            $this->jsonResponse(500, $error->getMessage());
        } else {
            $_SESSION['error'] = $error->getMessage();
            $this->redirect('error');
        }
    }

    /**
     * ارسال پاسخ JSON
     */
    private function jsonResponse($status, $message, $data = []) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode([
            'success' => $status === 200,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * تغییر مسیر
     */
    private function redirect($to) {
        header('Location: ' . BASE_URL . '/?page=' . $to);
        exit;
    }
}