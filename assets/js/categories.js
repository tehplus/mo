/**
 * اسکریپت مدیریت دسته‌بندی‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 08:01:16
 */

// منتظر میشیم تا صفحه کامل لود بشه
document.addEventListener('DOMContentLoaded', function() {
    // مقداردهی اولیه
    initializeTree();
    loadStatistics();
    
    // رویدادها
    document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);
});

// مقداردهی درخت دسته‌بندی‌ها
function initializeTree() {
    $('#categoriesTree').jstree({
        'core': {
            'data': {
                'url': apiEndpoint + '?action=getTree',
                'dataType': 'json'
            },
            'themes': {
                'name': 'default',
                'responsive': true
            },
            'check_callback': true
        },
        'plugins': ['dnd', 'search', 'state']
    }).on('select_node.jstree', function(e, data) {
        loadCategoryDetails(data.node.id);
    });
}

// بارگذاری آمار
function loadStatistics() {
    fetch(apiEndpoint + '?action=getStats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('mainCategoriesCount').textContent = data.data.mainCategories;
                document.getElementById('subCategoriesCount').textContent = data.data.subCategories;
                document.getElementById('totalProductsCount').textContent = data.data.totalProducts;
                document.getElementById('inactiveCategoriesCount').textContent = 
                    data.data.totalCategories - data.data.activeCategories;
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            showToast('خطا در دریافت آمار', 'error');
        });
}

// ذخیره دسته‌بندی جدید
function saveCategory() {
    const formData = {
        name: document.getElementById('categoryName').value,
        parent_id: document.getElementById('categoryParent').value,
        description: document.getElementById('categoryDescription').value,
        status: document.getElementById('categoryStatus').checked ? 1 : 0
    };

    fetch(apiEndpoint + '?action=add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('دسته‌بندی با موفقیت اضافه شد', 'success');
            $('#addCategoryModal').modal('hide');
            $('#categoriesTree').jstree(true).refresh();
            loadStatistics();
        } else {
            showToast(data.message || 'خطا در ذخیره دسته‌بندی', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving category:', error);
        showToast('خطا در ارتباط با سرور', 'error');
    });
}

// نمایش جزئیات دسته‌بندی
function loadCategoryDetails(categoryId) {
    fetch(apiEndpoint + '?action=get&id=' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const details = document.getElementById('categoryDetails');
                details.innerHTML = `
                    <h4>${data.category.name}</h4>
                    <p>${data.category.description || 'بدون توضیحات'}</p>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-primary" onclick="editCategory(${categoryId})">
                            <i class="fas fa-edit"></i> ویرایش
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCategory(${categoryId})">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading category details:', error);
            showToast('خطا در دریافت اطلاعات دسته‌بندی', 'error');
        });
}

// نمایش پیام
function showToast(message, type = 'info') {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: 'left',
        backgroundColor: type === 'success' ? '#28a745' : 
                        type === 'error' ? '#dc3545' : 
                        '#17a2b8'
    }).showToast();
}