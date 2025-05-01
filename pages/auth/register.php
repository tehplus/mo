<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        // بررسی اعتبار داده‌ها
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception('لطفاً تمام فیلدها را پر کنید.');
        }
        
        if ($password !== $confirm_password) {
            throw new Exception('رمز عبور و تکرار آن مطابقت ندارند.');
        }
        
        if (strlen($password) < 8) {
            throw new Exception('رمز عبور باید حداقل 8 کاراکتر باشد.');
        }
        
        // بررسی تکراری نبودن نام کاربری و ایمیل
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception('این نام کاربری یا ایمیل قبلاً ثبت شده است.');
        }
        
        // ثبت کاربر جدید
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $hashed_password]);
        
        $response['success'] = true;
        $response['message'] = 'ثبت نام با موفقیت انجام شد. لطفاً وارد شوید.';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام | سیستم حسابداری</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>ثبت نام در سیستم</h1>
                <p>برای ایجاد حساب کاربری، فرم زیر را تکمیل کنید</p>
            </div>
            
            <form id="registerForm" class="auth-form">
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <i class="fas fa-eye password-toggle"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">تکرار رمز عبور</label>
                    <div class="password-input">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <i class="fas fa-eye password-toggle"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="terms" required>
                        <label class="custom-control-label" for="terms">
                            با <a href="#" target="_blank">قوانین و مقررات</a> موافقم
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">ثبت نام</button>
            </form>
            
            <div class="auth-footer">
                <p>قبلاً ثبت نام کرده‌اید؟ <a href="?page=login">وارد شوید</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>