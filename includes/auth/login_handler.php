<?php
/**
 * پردازش درخواست ورود کاربر
 * @author tehplus
 * @version 1.0.1
 * @since 2025-05-02 10:37:59
 */

// تنظیمات اولیه
ini_set('display_errors', 1);
error_reporting(E_ALL);

// لود فایل‌های مورد نیاز
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/classes/Database.php';

// بررسی درخواست AJAX
if (!is_ajax()) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'دسترسی مستقیم به این فایل مجاز نیست'
    ]));
}

try {
    // دریافت و پاکسازی داده‌ها - حذف FILTER_SANITIZE_STRING منسوخ شده
    $username = trim(strip_tags($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'on';
    
    // اعتبارسنجی داده‌ها
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = 'نام کاربری یا ایمیل الزامی است';
    } elseif (strlen($username) > 100) {
        $errors['username'] = 'نام کاربری یا ایمیل نباید بیشتر از 100 کاراکتر باشد';
    }
    
    if (empty($password)) {
        $errors['password'] = 'رمز عبور الزامی است';
    }
    
    // اگر خطایی نبود
    if (empty($errors)) {
        try {
            // دریافت اتصال به دیتابیس
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            if (!$conn instanceof PDO) {
                throw new Exception('خطا در اتصال به پایگاه داده');
            }

            // جستجوی کاربر
            $stmt = $conn->prepare("
                SELECT id, username, email, password, full_name, is_active, permissions
                FROM users 
                WHERE (username = :username OR email = :email)
                AND is_active = 1
                LIMIT 1
            ");
            
            $stmt->execute([
                ':username' => $username,
                ':email' => $username
            ]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // ست کردن اطلاعات در سشن
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['permissions'] = json_decode($user['permissions'], true) ?? [];
                $_SESSION['last_activity'] = time();
                
                // اگر گزینه مرا به خاطر بسپار انتخاب شده
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    $stmt = $conn->prepare("
                        INSERT INTO remember_tokens (user_id, token, expires_at)
                        VALUES (:user_id, :token, :expires)
                    ");
                    
                    $stmt->execute([
                        ':user_id' => $user['id'],
                        ':token' => $token,
                        ':expires' => $expires
                    ]);
                    
                    // ست کردن کوکی
                    setcookie(
                        'remember_token',
                        $token,
                        [
                            'expires' => strtotime('+30 days'),
                            'path' => '/',
                            'domain' => '',
                            'secure' => SECURE_COOKIE,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]
                    );
                }
                
                // بروزرسانی آخرین ورود
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET last_login = NOW()
                    WHERE id = :user_id
                ");
                
                $stmt->execute([
                    ':user_id' => $user['id']
                ]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'ورود موفقیت‌آمیز',
                    'redirect' => '/?page=dashboard'
                ]);
                
            } else {
                echo json_encode([
                    'success' => false,
                    'errors' => [
                        'password' => 'نام کاربری/ایمیل یا رمز عبور اشتباه است'
                    ]
                ]);
            }
        } catch (PDOException $e) {
            error_log(sprintf(
                "[%s] Database Error in login_handler.php: %s",
                date('Y-m-d H:i:s'),
                $e->getMessage()
            ));
            
            echo json_encode([
                'success' => false,
                'message' => 'خطا در ارتباط با پایگاه داده'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
    
} catch (Exception $e) {
    error_log(sprintf(
        "[%s] Login Error: %s",
        date('Y-m-d H:i:s'),
        $e->getMessage()
    ));
    
    echo json_encode([
        'success' => false,
        'message' => DEVELOPMENT_MODE ? $e->getMessage() : 'خطای سیستمی'
    ]);
}