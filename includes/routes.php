<?php
/**
 * تنظیمات مسیریابی برنامه
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 13:30:00
 */

return [
    // صفحات عمومی
    'public' => [
        'login' => [
            'title' => 'ورود به سیستم',
            'path' => 'pages/auth/login.php',
            'layout' => 'auth'
        ],
        'register' => [
            'title' => 'ثبت نام',
            'path' => 'pages/auth/register.php',
            'layout' => 'auth'
        ],
        'forgot-password' => [
            'title' => 'بازیابی رمز عبور',
            'path' => 'pages/auth/forgot-password.php',
            'layout' => 'auth'
        ]
    ],
    
    // صفحات داشبورد
    'dashboard' => [
        'dashboard' => [
            'title' => 'داشبورد',
            'path' => 'pages/dashboard.php',
            'icon' => 'fas fa-tachometer-alt',
            'permission' => 'view_dashboard'
        ],
        'categories' => [
            'title' => 'مدیریت دسته‌بندی‌ها',
            'path' => 'pages/categories/index.php',
            'icon' => 'fas fa-folder-tree',
            'permission' => 'manage_categories'
        ],
        'products' => [
            'title' => 'مدیریت محصولات',
            'path' => 'pages/products/index.php',
            'icon' => 'fas fa-box',
            'permission' => 'manage_products'
        ]
    ],
    
    // صفحات خطا
    'error' => [
        '403' => [
            'title' => 'دسترسی غیرمجاز',
            'path' => 'pages/error/403.php',
            'layout' => 'error'
        ],
        '404' => [
            'title' => 'صفحه یافت نشد',
            'path' => 'pages/error/404.php',
            'layout' => 'error'
        ],
        '500' => [
            'title' => 'خطای سرور',
            'path' => 'pages/error/500.php',
            'layout' => 'error'
        ]
    ]
];