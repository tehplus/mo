<?php
/**
 * صفحه نمایش خطا
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : 'خطایی رخ داده است.';
unset($_SESSION['error']); // پاک کردن پیام خطا بعد از نمایش
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">خطا</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary">
            بازگشت به صفحه اصلی
        </a>
    </div>
</div>