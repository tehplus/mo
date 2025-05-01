<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        $db = (new Database())->getConnection();
        
        // دریافت و پاکسازی داده‌ها
        $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        
        // اعتبارسنجی داده‌ها
        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            throw new Exception('لطفاً تمام فیلدها را پر کنید.');
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
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}