#!/bin/bash

# ساخت ساختار پوشه‌ای
mkdir -p assets/css
mkdir -p assets/js
mkdir -p assets/fonts/anjoman
mkdir -p assets/img
mkdir -p config
mkdir -p includes
mkdir -p pages/accounting
mkdir -p auth

# ایجاد فایل‌ها در مسیرهای مشخص شده
touch assets/css/style.css assets/css/responsive.css
touch assets/js/main.js assets/js/sidebar.js
touch assets/fonts/anjoman/AnjomanMax-Medium.woff2 assets/fonts/anjoman/AnjomanMax-Regular.woff
touch config/database.php
touch includes/header.php includes/footer.php includes/sidebar.php
touch pages/dashboard.php pages/charts.php
touch pages/accounting/transactions.php pages/accounting/invoices.php pages/accounting/reports.php
touch auth/login.php auth/register.php
touch index.php
touch .htaccess

echo "ساختار پروژه کامل شد."