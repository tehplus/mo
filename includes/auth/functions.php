<?php
/**
 * توابع کمکی برای احراز هویت
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

// تابع بررسی کننده دسترسی کاربر
if (!function_exists('check_user_access')) {
    function check_user_access() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }
    }
}

// تابع پاکسازی ورودی‌ها
if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}