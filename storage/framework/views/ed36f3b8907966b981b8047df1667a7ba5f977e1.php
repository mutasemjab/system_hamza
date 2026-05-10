<?php $__env->startSection('title', 'إضافة محتوى'); ?>

<?php $__env->startSection('contentheader', 'إضافة لاعب لقائمة المحتوى'); ?>
<?php $__env->startSection('contentheaderlink', '<a href="'.route('social.index').'">السوشال ميديا</a>'); ?>
<?php $__env->startSection('contentheaderactive', 'إضافة'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <form method="POST" action="<?php echo e(route('social.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-plus text-accent mr-2"></i>
                    <span class="card-title">إضافة لاعب لجدول المحتوى</span>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>اللاعب <span class="text-danger">*</span></label>
                        <select name="player_id" class="form-control <?php $__errorArgs = ['player_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">اختر اللاعب...</option>
                            <?php $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($player->id); ?>" <?php echo e(old('player_id') == $player->id ? 'selected' : ''); ?>>
                                    <?php echo e($player->full_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['player_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group mb-3">
                        <label>نوع المحتوى <span class="text-danger">*</span></label>
                        <div class="type-grid">
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="type-option <?php echo e(old('content_type') == $key ? 'selected' : ''); ?>"
                                   style="--type-color: <?php echo e($meta['color']); ?>">
                                <input type="radio" name="content_type" value="<?php echo e($key); ?>"
                                       <?php echo e(old('content_type') == $key ? 'checked' : ''); ?>

                                       class="d-none">
                                <div class="type-icon">
                                    <i class="fab <?php echo e($meta['icon']); ?>"></i>
                                </div>
                                <div style="font-size:12px;font-weight:600;margin-top:6px"><?php echo e($meta['label']); ?></div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['content_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1" style="font-size:12px"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group mb-3">
                        <label>الحالة <span class="text-danger">*</span></label>
                        <div class="row">
                            <?php $__currentLoopData = ['pending' => ['label' => 'في الانتظار', 'icon' => 'fa-clock', 'color' => 'var(--accent)'], 'next' => ['label' => 'دوره الآن', 'icon' => 'fa-star', 'color' => 'var(--warning)'], 'published' => ['label' => 'منشور', 'icon' => 'fa-check-circle', 'color' => 'var(--success)']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-4">
                                <label class="status-option <?php echo e(old('status', 'pending') == $val ? 'selected' : ''); ?>"
                                       style="--s-color: <?php echo e($opt['color']); ?>">
                                    <input type="radio" name="status" value="<?php echo e($val); ?>"
                                           <?php echo e(old('status', 'pending') == $val ? 'checked' : ''); ?>

                                           class="d-none">
                                    <i class="fas <?php echo e($opt['icon']); ?>" style="font-size:18px"></i>
                                    <div style="font-size:12px;font-weight:600;margin-top:4px"><?php echo e($opt['label']); ?></div>
                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1" style="font-size:12px"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group mb-0">
                        <label>ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="أي ملاحظات..."><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="<?php echo e(route('social.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> إضافة للقائمة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.type-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}
@media (max-width: 576px) { .type-grid { grid-template-columns: repeat(3, 1fr); } }

.type-option {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 8px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    color: var(--text-muted);
}
.type-option:hover, .type-option.selected {
    border-color: var(--type-color, var(--accent));
    background: color-mix(in srgb, var(--type-color, var(--accent)) 8%, white);
    color: var(--type-color, var(--accent));
}
.type-icon { font-size: 24px; }

.status-option {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 8px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    color: var(--text-muted);
    display: block;
}
.status-option:hover, .status-option.selected {
    border-color: var(--s-color, var(--accent));
    background: color-mix(in srgb, var(--s-color, var(--accent)) 8%, white);
    color: var(--s-color, var(--accent));
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
// Type selection visual feedback
document.querySelectorAll('input[name="content_type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.type-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.type-option').classList.add('selected');
    });
});
// Status selection visual feedback
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.status-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.status-option').classList.add('selected');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/social/create.blade.php ENDPATH**/ ?>