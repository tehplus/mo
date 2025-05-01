<?php
// از اجرای مستقیم جلوگیری می‌کنیم
if (!defined('BASE_PATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit('No direct script access allowed');
}

// فقط درخواست‌های POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.0 405 Method Not Allowed');
    exit('Method not allowed');
}

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => ''];

try {
    require_once BASE_PATH . '/includes/classes/Database.php';
    $db = (new Database())->getConnection();
    
    // دریافت و پاکسازی داده‌ها
    $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = isset($_POST['terms']);

    // اعتبارسنجی داده‌ها
    if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
        throw new Exception('لطفاً تمام فیلدها را پر کنید.');
    }

    if (!$terms) {
        throw new Exception('پذیرش قوانین و مقررات الزامی است.');
    }

    if ($password !== $confirmPassword) {
        throw new Exception('رمز عبور و تکرار آن مطابقت ندارند.');
    }

    // اعتبارسنجی رمز عبور
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $password)) {
        throw new Exception('رمز عبور باید حداقل شامل:
            - 8 کاراکتر
            - یک حرف بزرگ
            - یک حرف کوچک
            - یک عدد
            - یک کاراکتر خاص (@$!%*?&#) باشد.');
    }

    // بررسی تکراری نبودن نام کاربری و ایمیل
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        throw new Exception('این نام کاربری یا ایمیل قبلاً ثبت شده است.');
    }
    
    // ذخیره کاربر جدید
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare(
        "INSERT INTO users (full_name, username, email, password, created_at) 
         VALUES (?, ?, ?, ?, NOW())"
    );
    
    if ($stmt->execute([$fullName, $username, $email, $hashedPassword])) {
        $response['success'] = true;
        $response['message'] = 'ثبت نام با موفقیت انجام شد.';
    } else {
        throw new Exception('خطا در ثبت اطلاعات.');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;