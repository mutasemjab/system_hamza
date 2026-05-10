<?php if(Session::has('success')): ?>
<div class="alert-toast alert-toast-success" id="successToast">
    <div class="alert-toast-icon"><i class="fas fa-check-circle"></i></div>
    <div class="alert-toast-body"><?php echo e(Session::get('success')); ?></div>
    <button class="alert-toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/includes/alerts/success.blade.php ENDPATH**/ ?>