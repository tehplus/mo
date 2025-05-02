<?php
/**
 * صفحه نمایش خطا
 * 
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
                <h1 class="m-0">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    خطا
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        بازگشت به صفحه اصلی
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>