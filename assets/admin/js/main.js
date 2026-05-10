/* Sys Admin — main.js */

$(function () {
    // Auto-dismiss toasts after 5 seconds
    setTimeout(function () {
        $('.alert-toast').fadeOut(400, function () { $(this).remove(); });
    }, 5000);

    // Select2 init
    if ($.fn.select2) {
        $('select:not(.no-select2)').select2({
            theme: 'default',
            width: '100%',
        });
    }

    // Confirm delete
    $(document).on('click', '[data-confirm]', function (e) {
        var msg = $(this).data('confirm') || 'Are you sure?';
        if (!confirm(msg)) { e.preventDefault(); }
    });

    // Tooltip init (Bootstrap 4)
    $('[data-toggle="tooltip"]').tooltip();
});
