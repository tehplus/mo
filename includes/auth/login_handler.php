<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/**
 * پردازش درخواست ورود کاربر
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');


// اگر درخواست Ajax نیست
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.1 403 Forbidden');
    die('دسترسی مستقیم به این فایل مجاز نیست');
}

// تنظیم header برای JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // دریافت و پاکسازی داده‌ها
    $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'on';
    
    // اعتبارسنجی داده‌ها
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = 'نام کاربری یا ایمیل الزامی است';
    }
    
    if (empty($password)) {
        $errors['password'] = 'رمز عبور الزامی است';
    }
    
    // اگر خطایی نبود
    if (empty($errors)) {
        try {
            $db = Database::getInstance();  // استفاده از getInstance
            $conn = $db->getConnection();
        } catch (Exception $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'خطا در اتصال به پایگاه داده'
            ]);
            exit;
        }
        
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // ست کردن اطلاعات در سشن
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            
            // اگر گزینه مرا به خاطر بسپار انتخاب شده
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                
                // ذخیره توکن در دیتابیس
                $stmt = $conn->prepare("
                    INSERT INTO remember_tokens (user_id, token, expires_at) 
                    VALUES (?, ?, ?)
                ");
                
                if ($stmt->execute([$user['id'], $token, $expires])) {
                    // ست کردن کوکی
                    setcookie(
                        'remember_token',
                        $token,
                        strtotime('+30 days'),
                        '/',
                        '',
                        true,  // Only through HTTPS
                        true   // HttpOnly
                    );
                }
            }
            
            // بروزرسانی آخرین ورود
            $stmt = $conn->prepare("
                UPDATE users 
                SET last_login = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$user['id']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'ورود موفقیت‌آمیز'
            ]);
            
        } else {
            echo json_encode([
                'success' => false,
                'errors' => [
                    'password' => 'نام کاربری/ایمیل یا رمز عبور اشتباه است'
                ]
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Database Error in login_handler.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'خطا در ارتباط با پایگاه داده'
    ]);
} catch (Exception $e) {
    error_log("General Error in login_handler.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}