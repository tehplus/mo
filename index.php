
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم حسابداری حرفه‌ای</title>
    <link rel="stylesheet" href="assets/css/landing.css">
    <!-- تعریف فونت‌آیکون‌ها -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- لینک SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- هدر -->
    <header class="header">
        <nav class="nav-container">
            <a href="#" class="logo">حسابدار هوشمند</a>
            <div class="nav-menu">
                <a href="#features" class="nav-link">ویژگی‌ها</a>
                <a href="#pricing" class="nav-link">تعرفه‌ها</a>
                <a href="#testimonials" class="nav-link">نظرات کاربران</a>
                <a href="#contact" class="nav-link">تماس با ما</a>
                <a href="?page=login.php" class="nav-link btn btn-secondary">ورود</a>
                <a href="?page=register" class="nav-link btn btn-primary">ثبت‌نام</a>
            </div>
        </nav>
    </header>

    <!-- بخش قهرمان -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">مدیریت هوشمند مالی کسب و کار شما</h1>
            <p class="hero-subtitle">با استفاده از سیستم حسابداری ما، مدیریت مالی کسب و کار خود را آسان کنید</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">شروع رایگان</a>
                <a href="#features" class="btn btn-secondary">بیشتر بدانید</a>
            </div>
        </div>
    </section>

    <!-- بخش ویژگی‌ها -->
    <section id="features" class="features">
        <div class="section-header">
            <h2>ویژگی‌های برتر</h2>
            <p>امکانات پیشرفته برای مدیریت بهتر کسب و کار شما</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>گزارش‌گیری پیشرفته</h3>
                <p>تحلیل داده‌های مالی با نمودارهای تعاملی و گزارش‌های سفارشی</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-receipt"></i>
                <h3>صدور فاکتور</h3>
                <p>ایجاد و مدیریت فاکتورهای حرفه‌ای با قالب‌های متنوع</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-tasks"></i>
                <h3>مدیریت حساب‌ها</h3>
                <p>کنترل و پیگیری تمام حساب‌های بانکی و تراکنش‌ها</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>دسترسی موبایل</h3>
                <p>مدیریت حساب‌ها در هر زمان و مکان با رابط کاربری ریسپانسیو</p>
            </div>
        </div>
    </section>

    <!-- بخش آمار -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number" data-target="15000">0</div>
                <div class="stat-label">کاربر فعال</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="1000000">0</div>
                <div class="stat-label">فاکتور صادر شده</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="5000">0</div>
                <div class="stat-label">شرکت</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="99">0</div>
                <div class="stat-label">رضایت مشتریان</div>
            </div>
        </div>
    </section>

    <!-- بخش تعرفه‌ها -->
    <section id="pricing" class="pricing">
        <div class="section-header">
            <h2>تعرفه‌های اشتراک</h2>
            <p>بسته متناسب با نیاز خود را انتخاب کنید</p>
        </div>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>پایه</h3>
                <div class="price">رایگان</div>
                <ul class="features-list">
                    <li>مدیریت فاکتورها</li>
                    <li>گزارش‌های پایه</li>
                    <li>یک کاربر</li>
                    <li>پشتیبانی ایمیلی</li>
                </ul>
                <a href="register.php" class="btn btn-primary">شروع کنید</a>
            </div>
            <div class="pricing-card popular">
                <h3>حرفه‌ای</h3>
                <div class="price">۲۹۹,۰۰۰ تومان</div>
                <ul class="features-list">
                    <li>تمام امکانات پایه</li>
                    <li>گزارش‌های پیشرفته</li>
                    <li>۵ کاربر</li>
                    <li>پشتیبانی تلفنی</li>
                </ul>
                <a href="register.php" class="btn btn-primary">انتخاب پلن</a>
            </div>
            <div class="pricing-card">
                <h3>سازمانی</h3>
                <div class="price">تماس بگیرید</div>
                <ul class="features-list">
                    <li>تمام امکانات حرفه‌ای</li>
                    <li>API اختصاصی</li>
                    <li>کاربران نامحدود</li>
                    <li>پشتیبانی VIP</li>
                </ul>
                <a href="#contact" class="btn btn-primary">درخواست مشاوره</a>
            </div>
        </div>
    </section>

    <!-- بخش نظرات کاربران -->
    <section id="testimonials" class="testimonials">
        <div class="section-header">
            <h2>نظرات کاربران</h2>
            <p>تجربه مشتریان ما از استفاده از سیستم</p>
        </div>
        <div class="testimonials-slider">
            <div class="testimonial">
                <div class="testimonial-content">
                    <p>استفاده از این سیستم باعث صرفه‌جویی زمانی زیادی در کار ما شده است.</p>
                    <div class="testimonial-author">
                        <img src="assets/img/user1.jpg" alt="کاربر 1">
                        <div class="author-info">
                            <h4>علی محمدی</h4>
                            <p>مدیر مالی شرکت پارس</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- سایر نظرات -->
        </div>
        <button class="testimonial-prev">&lt;</button>
        <button class="testimonial-next">&gt;</button>
    </section>

    <!-- بخش تماس با ما -->
    <section id="contact" class="contact">
        <div class="section-header">
            <h2>تماس با ما</h2>
            <p>در تماس باشید</p>
        </div>
        <div class="contact-container">
            <form id="contact-form" class="contact-form">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="نام و نام خانوادگی" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="ایمیل" required>
                </div>
                <div class="form-group">
                    <textarea id="message" name="message" placeholder="پیام شما" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">ارسال پیام</button>
            </form>
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>تهران، خیابان ولیعصر، پلاک ۱۲۳</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <p>۰۲۱-۱۲۳۴۵۶۷۸</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <p>info@yourdomain.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- فوتر -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>درباره ما</h3>
                <p>سیستم حسابداری هوشمند، راهکاری جامع برای مدیریت مالی کسب و کارها</p>
            </div>
            <div class="footer-section">
                <h3>لینک‌های مفید</h3>
                <ul>
                    <li><a href="#features">ویژگی‌ها</a></li>
                    <li><a href="#pricing">تعرفه‌ها</a></li>
                    <li><a href="#testimonials">نظرات</a></li>
                    <li><a href="#contact">تماس</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>شبکه‌های اجتماعی</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; ۱۴۰۲ تمامی حقوق محفوظ است.</p>
        </div>
    </footer>

    <!-- اسکریپت‌ها -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/landing.js"></script>
    <script>
        // مدیریت فرم تماس
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'با تشکر!',
                text: 'پیام شما با موفقیت ارسال شد.',
                icon: 'success',
                confirmButtonText: 'باشه'
            });

            this.reset();
        });
    </script>
</body>
</html>