<?php
// بررسی دسترسی
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">داشبورد</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- آمار کلی -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>0</h3>
                            <p>تراکنش‌های امروز</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="?page=transactions" class="small-box-footer">
                            اطلاعات بیشتر <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>0</h3>
                            <p>فاکتورهای این ماه</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <a href="?page=invoices" class="small-box-footer">
                            اطلاعات بیشتر <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>0</h3>
                            <p>گزارشات آماده</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="?page=reports" class="small-box-footer">
                            اطلاعات بیشتر <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>0</h3>
                            <p>اعلان‌های جدید</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            اطلاعات بیشتر <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- نمودار درآمد و هزینه -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">نمودار درآمد و هزینه</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <canvas id="income-expense-chart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">آخرین تراکنش‌ها</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>تاریخ</th>
                                        <th>شرح</th>
                                        <th>مبلغ</th>
                                        <th>نوع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">تراکنشی یافت نشد</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // نمودار درآمد و هزینه
    const ctx = document.getElementById('income-expense-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
            datasets: [{
                label: 'درآمد',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: '#28a745',
                tension: 0.1
            }, {
                label: 'هزینه',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: '#dc3545',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>