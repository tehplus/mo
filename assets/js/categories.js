/**
 * مدیریت دسته‌بندی‌ها
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

document.addEventListener('DOMContentLoaded', function() {
    // آماده‌سازی درخت دسته‌بندی‌ها
    initializeCategoriesTree();
    
    // رویداد دکمه ذخیره دسته‌بندی جدید
    document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);
    
    // لود آمار اولیه
    loadCategoriesStatistics();
});

/**
 * راه‌اندازی درخت دسته‌بندی‌ها
 */
function initializeCategoriesTree() {
    const treeContainer = document.getElementById('categoriesTree');
    if (!treeContainer) return;
    
    // درخواست لیست دسته‌بندی‌ها
    fetch(BASE_URL + '/api/categories/list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCategoriesTree(data.categories);
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
 * نمایش درخت دسته‌بندی‌ها
 */
function renderCategoriesTree(categories) {
    const treeContainer = document.getElementById('categoriesTree');
    if (!categories || !treeContainer) return;
    
    // اینجا از کتابخانه jstree استفاده می‌کنیم
    $(treeContainer).jstree({
        'core': {
            'data': categories,
            'themes': {
                'name': 'default',
                'responsive': true
            }
        },
        'plugins': ['dnd', 'search', 'state', 'types', 'wholerow']
    }).on('select_node.jstree', function(e, data) {
        loadCategoryDetails(data.node.id);
    });
}

/**
 * لود اطلاعات یک دسته‌بندی
 */
function loadCategoryDetails(categoryId) {
    fetch(BASE_URL + '/api/categories/get/' + categoryId)
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
    const detailsContainer = document.getElementById('categoryDetails');
    if (!detailsContainer) return;
    
    detailsContainer.innerHTML = `
        <div class="category-details">
            <h4 class="mb-3">${category.name}</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>وضعیت:</strong> ${category.status ? 'فعال' : 'غیرفعال'}</p>
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
                <button class="btn btn-primary btn-sm" onclick="editCategory(${category.id})">
                    <i class="fas fa-edit"></i> ویرایش
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.id})">
                    <i class="fas fa-trash"></i> حذف
                </button>
            </div>
        </div>
    `;
}

/**
 * لود آمار دسته‌بندی‌ها
 */
function loadCategoriesStatistics() {
    fetch(BASE_URL + '/api/categories/stats')
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
    document.getElementById('mainCategoriesCount').textContent = stats.main_categories || 0;
    document.getElementById('subCategoriesCount').textContent = stats.sub_categories || 0;
    document.getElementById('totalProductsCount').textContent = stats.total_products || 0;
    document.getElementById('inactiveCategoriesCount').textContent = stats.inactive_categories || 0;
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
    
    fetch(BASE_URL + '/api/categories/create', {
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
            
            // بروزرسانی درخت و آمار
            initializeCategoriesTree();
            loadCategoriesStatistics();
            
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