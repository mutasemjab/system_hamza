<?php if(Session::has('error')): ?>
<div class="alert-toast alert-toast-danger" id="errorToast">
    <div class="alert-toast-icon"><i class="fas fa-exclamation-circle"></i></div>
    <div class="alert-toast-body"><?php echo e(Session::get('error')); ?></div>
    <button class="alert-toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php endif; ?>
<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="alert-toast alert-toast-danger">
    <div class="alert-toast-icon"><i class="fas fa-exclamation-circle"></i></div>
    <div class="alert-toast-body"><?php echo e($message); ?></div>
    <button class="alert-toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/includes/alerts/error.blade.php ENDPATH**/ ?>