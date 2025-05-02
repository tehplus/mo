<?php
/**
 * مدیریت دسترسی‌های کاربران
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 07:19:46
 */

// تعریف دسترسی‌های سیستم
$system_permissions = [
    'manage_categories' => [
        'title' => 'مدیریت دسته‌بندی‌ها',
        'description' => 'دسترسی به مدیریت دسته‌بندی‌های محصولات'
    ],
    // سایر دسترسی‌ها...
];

/**
 * بررسی دسترسی کاربر
 * 
 * @param string $permission نام دسترسی
 * @return boolean نتیجه بررسی
 */
function checkUserPermission($permission) {
    // اگر کاربر لاگین نکرده باشد
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // اگر کاربر ادمین باشد
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        return true;
    }

    // بررسی دسترسی‌های کاربر
    if (isset($_SESSION['permissions']) && is_array($_SESSION['permissions'])) {
    if (in_array($permission, $_SESSION['permissions'])) {
        error_log(sprintf(
            "[%s] Permission granted for user %s: %s", 
            date('Y-m-d H:i:s'),
            $_SESSION['username'] ?? 'unknown',
            $permission
        ));
        return true;
    }
    error_log(sprintf(
        "[%s] Permission denied for user %s: %s", 
        date('Y-m-d H:i:s'),
        $_SESSION['username'] ?? 'unknown',
        $permission
    ));
    return false;
}

    return false;
}

/**
 * دریافت لیست همه دسترسی‌ها
 * 
 * @return array لیست دسترسی‌ها
 */
function getAllPermissions() {
    global $system_permissions;
    return $system_permissions;
}

/**
 * دریافت اطلاعات یک دسترسی خاص
 * 
 * @param string $permission نام دسترسی
 * @return array|null اطلاعات دسترسی
 */
function getPermissionInfo($permission) {
    global $system_permissions;
    return isset($system_permissions[$permission]) ? $system_permissions[$permission] : null;
}