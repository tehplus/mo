document.addEventListener('DOMContentLoaded', function() {
    // انیمیشن اسکرول هدر
    const header = document.querySelector('.header');
    const heroSection = document.querySelector('.hero');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // انیمیشن اعداد در بخش آمار
    const stats = document.querySelectorAll('.stat-number');
    const statsSection = document.querySelector('.stats');
    
    const animateStats = () => {
        stats.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-target'));
            const duration = 2000; // مدت زمان انیمیشن به میلی‌ثانیه
            const increment = target / (duration / 16); // 60fps
            let current = 0;

            const updateCount = () => {
                if (current < target) {
                    current += increment;
                    stat.textContent = Math.ceil(current).toLocaleString();
                    requestAnimationFrame(updateCount);
                } else {
                    stat.textContent = target.toLocaleString();
                }
            };

            updateCount();
        });
    };

    // اجرای انیمیشن وقتی بخش آمار در دید قرار می‌گیرد
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    });

    observer.observe(statsSection);

    // اسکرول نرم برای لینک‌های ناوبری
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // انیمیشن ظاهر شدن کارت‌های ویژگی‌ها
    const featureCards = document.querySelectorAll('.feature-card');
    
    const fadeInObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    featureCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        fadeInObserver.observe(card);
    });

    // اسلایدر نظرات کاربران
    let currentTestimonial = 0;
    const testimonials = document.querySelectorAll('.testimonial');
    const totalTestimonials = testimonials.length;

    function showTestimonial(index) {
        testimonials.forEach((testimonial, i) => {
            testimonial.style.transform = `translateX(${100 * (i - index)}%)`;
        });
    }

    // دکمه‌های اسلایدر
    document.querySelector('.testimonial-next').addEventListener('click', () => {
        currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
        showTestimonial(currentTestimonial);
    });

    document.querySelector('.testimonial-prev').addEventListener('click', () => {
        currentTestimonial = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
        showTestimonial(currentTestimonial);
    });

    // اجرای اولیه اسلایدر
    showTestimonial(0);
});