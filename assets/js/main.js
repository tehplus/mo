function showNotification(title, message, type = 'success') {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonText: 'تایید',
        customClass: {
            confirmButton: 'btn btn-primary',
            popup: 'swal-rtl'
        }
    });
}