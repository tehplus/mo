// تنظیمات نمودار فروش
function initSalesChart() {
    const options = {
        series: [{
            name: 'فروش',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
            fontFamily: 'IRANSans, Tahoma, sans-serif',
            dir: 'rtl'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'روند فروش',
            align: 'right',
            style: {
                fontFamily: 'IRANSans, Tahoma, sans-serif'
            }
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر'],
        },
        yaxis: {
            title: {
                text: 'مبلغ (تومان)',
                style: {
                    fontFamily: 'IRANSans, Tahoma, sans-serif'
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
}

// تنظیمات نمودار مشتریان
function initCustomerChart() {
    const options = {
        series: [{
            name: 'مشتریان جدید',
            data: [10, 15, 12, 20, 18, 25, 22, 30, 28]
        }],
        chart: {
            height: 350,
            type: 'bar',
            fontFamily: 'IRANSans, Tahoma, sans-serif',
            dir: 'rtl'
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر'],
        },
        yaxis: {
            title: {
                text: 'تعداد مشتریان',
                style: {
                    fontFamily: 'IRANSans, Tahoma, sans-serif'
                }
            }
        },
        fill: {
            opacity: 1
        },
        title: {
            text: 'مشتریان جدید',
            align: 'right',
            style: {
                fontFamily: 'IRANSans, Tahoma, sans-serif'
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#customerChart"), options);
    chart.render();
}

// رویداد تغییر بازه زمانی نمودارها
document.querySelectorAll('.chart-period-select').forEach(select => {
    select.addEventListener('change', function() {
        const chartType = this.getAttribute('data-chart');
        const period = this.value;
        updateChart(chartType, period);
    });
});

// بروزرسانی نمودارها
function updateChart(chartType, period) {
    // در اینجا می‌توانید درخواست AJAX برای دریافت داده‌های جدید ارسال کنید
    console.log(`Updating ${chartType} chart for period: ${period}`);
}

// راه‌اندازی نمودارها در لود صفحه
document.addEventListener('DOMContentLoaded', function() {
    initSalesChart();
    initCustomerChart();
});