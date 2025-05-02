/**
 * اسکریپت‌های صفحه دسته‌بندی‌ها
 */

document.addEventListener('DOMContentLoaded', function() {
    // راه‌اندازی اولیه صفحه
    initializePage();
    
    // رویدادهای دکمه‌ها
    setupEventListeners();
    
    // لود آمار اولیه
    loadStatistics();
});

/**
 * راه‌اندازی اولیه صفحه
 */
function initializePage() {
    // راه‌اندازی تولتیپ‌ها
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // راه‌اندازی درخت دسته‌بندی‌ها
    initializeTree();
}

/**
 * راه‌اندازی درخت دسته‌بندی‌ها
 */
function initializeTree() {
    fetch(baseUrl + '/api/categories/list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createJsTree(data.categories);
                updateParentSelect(data.categories);
            } else {
                showError('خطا در دریافت لیست دسته‌بندی‌ها');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('خطا در ارتباط با سرور');
        });
}

/**
 * ایجاد درخت با jsTree
 */
function createJsTree(categories) {
    $('#categoriesTree').jstree('destroy');
    $('#categoriesTree').jstree({
        'core': {
            'data': categories,
            'themes': {
                'name': 'default',
                'responsive': true
            }
        },
        'plugins': ['wholerow', 'search', 'state'],
        'search': {
            'show_only_matches': true
        }
    }).on('select_node.jstree', function(e, data) {
        loadCategoryDetails(data.node.id);
    });
}

/**
 * بروزرسانی لیست والدین در مودال
 */
function updateParentSelect(categories) {
    const select = document.getElementById('categoryParent');
    select.innerHTML = '<option value="0">بدون والد (دسته اصلی)</option>';
    
    categories.forEach(category => {
        if (category.parent === '#') {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.text;
            select.appendChild(option);
        }
    });
}

/**
 * لود اطلاعات یک دسته‌بندی
 */
function loadCategoryDetails(categoryId) {
    fetch(baseUrl + '/api/categories/get/' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCategoryDetails(data.category);
            } else {
                showError('خطا در دریافت اطلاعات دسته‌بندی');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('خطا در ارتباط با سرور');
        });
}

/**
 * نمایش اطلاعات دسته‌بندی
 */
function showCategoryDetails(category) {
    const container = document.getElementById('categoryDetails');
    
    const statusBadge = category.status 
        ? '<span class="badge bg-success">فعال</span>' 
        : '<span class="badge bg-danger">غیرفعال</span>';
        
    container.innerHTML = `
        <div class="category-details">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="m-0">${category.name}</h4>
                ${statusBadge}
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>والد:</strong> ${category.parent_name || 'ندارد'}</p>
                    <p><strong>تعداد محصولات:</strong> ${category.products_count}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>تاریخ ایجاد:</strong> ${category.created_at}</p>
                    <p><strong>آخرین بروزرسانی:</strong> ${category.updated_at}</p>
                </div>
            </div>
            <div class="mt-3">
                <p><strong>توضیحات:</strong></p>
                <p>${category.description || 'بدون توضیحات'}</p>
            </div>
            <div class="mt-4">
                <button class="btn btn-warning btn-sm" onclick="editCategory(${category.id})">
                    <i class="fas fa-edit"></i> ویرایش
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete(${category.id})">
                    <i class="fas fa-trash"></i> حذف
                </button>
            </div>
        </div>
    `;
}

/**
 * لود آمار
 */
function loadStatistics() {
    fetch(baseUrl + '/api/categories/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatistics(data.stats);
            }
        })
        .catch(error => console.error('Error:', error));
}

/**
 * بروزرسانی آمار
 */
function updateStatistics(stats) {
    document.getElementById('mainCategoriesCount').textContent = stats.main_categories;
    document.getElementById('subCategoriesCount').textContent = stats.sub_categories;
    document.getElementById('totalProductsCount').textContent = stats.total_products;
    document.getElementById('inactiveCategoriesCount').textContent = stats.inactive_categories;
}

/**
 * تنظیم رویدادها
 */
function setupEventListeners() {
    // دکمه ذخیره دسته‌بندی جدید
    document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);
    
    // دکمه تازه‌سازی درخت
    document.querySelector('.card-tools .btn-tool').addEventListener('click', function() {
        initializeTree();
        loadStatistics();
    });
}

/**
 * ذخیره دسته‌بندی جدید
 */
function saveCategory() {
    const formData = {
        name: document.getElementById('categoryName').value,
        parent_id: document.getElementById('categoryParent').value,
        description: document.getElementById('categoryDescription').value,
        status: document.getElementById('categoryStatus').checked
    };
    
    fetch(baseUrl + '/api/categories/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // بستن مودال
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();
            
            // پاک کردن فرم
            document.getElementById('addCategoryForm').reset();
            
            // بروزرسانی درخت و آمار
            initializeTree();
            loadStatistics();
            
            showSuccess('دسته‌بندی با موفقیت ایجاد شد');
        } else {
            showError(data.message || 'خطا در ایجاد دسته‌بندی');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('خطا در ارتباط با سرور');
    });
}

/**
 * نمایش پیام موفقیت
 */
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'موفقیت',
        text: message,
        confirmButtonText: 'تایید'
    });
}

/**
 * نمایش پیام خطا
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'خطا',
        text: message,
        confirmButtonText: 'تایید'
    });
}

/**
 * تایید حذف دسته‌بندی
 */
function confirmDelete(categoryId) {
    Swal.fire({
        title: 'آیا مطمئن هستید؟',
        text: 'این دسته‌بندی و تمام زیردسته‌های آن حذف خواهند شد',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، حذف شود',
        cancelButtonText: 'انصراف'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCategory(categoryId);
        }
    });
}

/**
 * حذف دسته‌بندی
 */
function deleteCategory(categoryId) {
    // این قسمت رو بعداً پیاده‌سازی می‌کنیم
    showError('این قابلیت هنوز پیاده‌سازی نشده است');
}

/**
 * ویرایش دسته‌بندی
 */
function editCategory(categoryId) {
    // این قسمت رو بعداً پیاده‌سازی می‌کنیم
    showError('این قابلیت هنوز پیاده‌سازی نشده است');
}