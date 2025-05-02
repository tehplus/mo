<?php
/**
 * توابع کمکی برای احراز هویت
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 04:31:25
 */

if (!function_exists('sanitize_input')) {
    /**
     * پاکسازی ورودی‌های کاربر
     * @param string $input متن ورودی
     * @return string متن پاکسازی شده
     */
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('validate_username')) {
    /**
     * اعتبارسنجی نام کاربری
     * @param string $username نام کاربری
     * @return bool
     */
    function validate_username($username) {
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }
}

if (!function_exists('validate_email')) {
    /**
     * اعتبارسنجی ایمیل
     * @param string $email آدرس ایمیل
     * @return bool
     */
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('validate_password')) {
    /**
     * اعتبارسنجی رمز عبور
     * @param string $password رمز عبور
     * @return bool
     */
    function validate_password($password) {
        // حداقل 8 کاراکتر، شامل حروف کوچک، بزرگ و اعداد
        return strlen($password) >= 8 &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[0-9]/', $password);
    }
}

if (!function_exists('generate_token')) {
    /**
     * تولید توکن امنیتی
     * @param int $length طول توکن
     * @return string
     */
    function generate_token($length = 32) {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('check_remember_token')) {
    /**
     * بررسی توکن "مرا به خاطر بسپار"
     * @param PDO $db اتصال دیتابیس
     * @param string $token توکن
     * @return array|false اطلاعات کاربر یا false
     */
    function check_remember_token($db, $token) {
        try {
            $stmt = $db->prepare("
                SELECT u.* 
                FROM users u 
                JOIN remember_tokens rt ON u.id = rt.user_id 
                WHERE rt.token = ? AND rt.expires_at > NOW()
            ");
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Remember Token Error: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('set_remember_cookie')) {
    /**
     * تنظیم کوکی "مرا به خاطر بسپار"
     * @param string $token توکن
     * @param int $days تعداد روز
     * @return bool
     */
    function set_remember_cookie($token, $days = 30) {
        return setcookie(
            'remember_token',
            $token,
            [
                'expires' => time() + ($days * 86400),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }
}

if (!function_exists('get_user_by_id')) {
    /**
     * دریافت اطلاعات کاربر با شناسه
     * @param PDO $db اتصال دیتابیس
     * @param int $id شناسه کاربر
     * @return array|false
     */
    function get_user_by_id($db, $id) {
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND status = 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get User Error: " . $e->getMessage());
            return false;
        }
    }
}