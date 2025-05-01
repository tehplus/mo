document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // پاک کردن خطاهای قبلی
    document.querySelectorAll('.error-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    
    // اعتبارسنجی قوانین
    if (!document.getElementById('terms').checked) {
        Swal.fire({
            title: 'خطا!',
            text: 'لطفاً قوانین و مقررات را مطالعه و قبول کنید.',
            icon: 'error',
            confirmButtonText: 'باشه'
        });
        return;
    }
    
    try {
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // غیرفعال کردن دکمه
        submitBtn.disabled = true;
        submitBtn.querySelector('.loading-spinner').style.display = 'inline-block';
        submitBtn.querySelector('.btn-text').style.display = 'none';
        
        const response = await fetch('includes/auth/register_handler.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            await Swal.fire({
                title: 'موفق!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'باشه'
            });
            window.location.href = '?page=login';
        } else {
            // نمایش خطاها
            if (data.errors) {
                Object.entries(data.errors).forEach(([field, message]) => {
                    const errorElement = document.querySelector(`#${field} + .error-feedback`);
                    if (errorElement) {
                        errorElement.textContent = message;
                        errorElement.style.display = 'block';
                    }
                });
            }
            
            // اگر خطای عمومی وجود داشت
            if (data.errors.general) {
                Swal.fire({
                    title: 'خطا!',
                    text: data.errors.general,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            title: 'خطا!',
            text: 'خطا در ارتباط با سرور',
            icon: 'error',
            confirmButtonText: 'باشه'
        });
    } finally {
        // فعال کردن مجدد دکمه
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = false;
        submitBtn.querySelector('.loading-spinner').style.display = 'none';
        submitBtn.querySelector('.btn-text').style.display = 'inline';
    }
});

// نمایش/مخفی کردن رمز عبور
document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const type = input.getAttribute('type');
        input.setAttribute('type', type === 'password' ? 'text' : 'password');
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});