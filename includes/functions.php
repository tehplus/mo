<?php
/**
 * توابع کمکی عمومی
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 04:50:00
 */

if (!function_exists('createAlert')) {
    /**
     * ایجاد پیام هشدار
     * @param string $type نوع پیام (success, error, warning, info)
     * @param string $message متن پیام
     */
    function createAlert($type, $message) {
        if (!isset($_SESSION['alerts'])) {
            $_SESSION['alerts'] = [];
        }
        $_SESSION['alerts'][] = [
            'type' => $type,
            'message' => $message
        ];
    }
}

if (!function_exists('jdate')) {
    /**
     * تبدیل تاریخ میلادی به شمسی
     * @param string $format فرمت خروجی
     * @param int|string $timestamp تایم استمپ یا تاریخ میلادی
     * @return string تاریخ شمسی
     */
    function jdate($format, $timestamp = null) {
        if ($timestamp === null) {
            $timestamp = time();
        } else if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        
        // تبدیل اعداد انگلیسی به فارسی
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        // نام ماه‌های فارسی
        $months = [
            'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
            'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
        ];
        
        // نام روزهای هفته
        $days = [
            'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 
            'پنج‌شنبه', 'جمعه', 'شنبه'
        ];
        
        // تبدیل به تاریخ شمسی
        $date = date($format, $timestamp);
        
        // جایگزینی اعداد فارسی
        $date = str_replace($english, $persian, $date);
        
        return $date;
    }
}

if (!function_exists('formatNumber')) {
    /**
     * فرمت کردن اعداد با جداکننده هزارگان
     * @param mixed $number عدد ورودی
     * @param int $decimals تعداد اعشار
     * @return string
     */
    function formatNumber($number, $decimals = 0) {
        return number_format($number, $decimals, '/', ',');
    }
}

if (!function_exists('sanitize')) {
    /**
     * پاکسازی ورودی کاربر
     * @param string $input متن ورودی
     * @return string
     */
    function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('isAjax')) {
    /**
     * بررسی درخواست Ajax
     * @return bool
     */
    function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

if (!function_exists('redirectTo')) {
    /**
     * تغییر مسیر به صفحه دیگر
     * @param string $page نام صفحه
     * @param array $params پارامترهای اضافی
     */
    function redirectTo($page, $params = []) {
        $url = '?page=' . $page;
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('getCurrentUser')) {
    /**
     * دریافت اطلاعات کاربر جاری
     * @return array|null
     */
    function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("
                SELECT id, username, email, full_name, role, last_login 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Get Current User Error: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('hasPermission')) {
    /**
     * بررسی دسترسی کاربر
     * @param string $permission نام دسترسی
     * @return bool
     */
    function hasPermission($permission) {
        $user = getCurrentUser();
        if (!$user) return false;
        
        // فعلاً فقط admin دسترسی کامل دارد
        return $user['role'] === 'admin';
    }
}

if (!function_exists('generateInvoiceNumber')) {
    /**
     * تولید شماره فاکتور یکتا
     * @return string
     */
    function generateInvoiceNumber() {
        $prefix = date('Ymd');
        $rand = mt_rand(1000, 9999);
        return $prefix . $rand;
    }
}