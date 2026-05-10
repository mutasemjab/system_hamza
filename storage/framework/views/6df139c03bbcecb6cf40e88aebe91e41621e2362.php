<?php $__env->startSection('title', 'إضافة اشتراك'); ?>

<?php $__env->startSection('contentheader', 'إضافة اشتراك جديد'); ?>
<?php $__env->startSection('contentheaderactive', 'إضافة'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="<?php echo e(route('admin.subscription.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-invoice-dollar text-accent mr-2"></i>
                    <span class="card-title">بيانات الاشتراك</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
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
                            <?php if($players->isEmpty()): ?>
                                <div class="text-warning mt-1" style="font-size:12px">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    جميع اللاعبين لديهم اشتراكات. أضف لاعباً جديداً أولاً.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>إجمالي الاشتراك (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" step="0.01" min="0"
                                   class="form-control <?php $__errorArgs = ['total_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('total_amount', 0)); ?>" id="totalAmount">
                            <?php $__errorArgs = ['total_amount'];
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

                        <div class="col-md-6 mb-3">
                            <label>المبلغ المدفوع (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="paid_amount" step="0.01" min="0"
                                   class="form-control <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('paid_amount', 0)); ?>" id="paidAmount">
                            <?php $__errorArgs = ['paid_amount'];
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

                        
                        <div class="col-12 mb-3">
                            <div class="remaining-box">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:13px;color:var(--text-muted)">المبلغ المتبقي</span>
                                    <strong id="remainingDisplay" style="font-size:16px;color:var(--danger)">0 د.أ</strong>
                                </div>
                                <div style="background:#e2e8f0;border-radius:99px;height:8px;margin-top:10px;overflow:hidden">
                                    <div id="progressBar" style="height:100%;width:0%;border-radius:99px;background:var(--accent);transition:width .3s"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>آخر تاريخ دفع</label>
                            <input type="date" name="last_payment_date"
                                   class="form-control <?php $__errorArgs = ['last_payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('last_payment_date')); ?>">
                            <?php $__errorArgs = ['last_payment_date'];
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

                        <div class="col-md-6 mb-3">
                            <label>حالة الاشتراك <span class="text-danger">*</span></label>
                            <select name="status" class="form-control no-select2 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="active"  <?php echo e(old('status','active') == 'active'  ? 'selected' : ''); ?>>✅ فعال</option>
                                <option value="late"    <?php echo e(old('status') == 'late'    ? 'selected' : ''); ?>>⚠️ متأخر</option>
                                <option value="expired" <?php echo e(old('status') == 'expired' ? 'selected' : ''); ?>>❌ منتهي</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <div class="col-md-6 mb-3">
                            <label>تاريخ البداية</label>
                            <input type="date" name="start_date"
                                   class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('start_date')); ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تاريخ الانتهاء</label>
                            <input type="date" name="end_date"
                                   class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('end_date')); ?>">
                        </div>

                        <div class="col-12 mb-0">
                            <label>ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="ملاحظات حول الاشتراك..."><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.subscription.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> حفظ الاشتراك
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.remaining-box {
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px 18px;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
function updateRemaining() {
    const total = parseFloat(document.getElementById('totalAmount').value) || 0;
    const paid  = parseFloat(document.getElementById('paidAmount').value) || 0;
    const remaining = Math.max(0, total - paid);
    const pct = total > 0 ? Math.min(100, Math.round((paid / total) * 100)) : 0;
    document.getElementById('remainingDisplay').textContent = remaining.toLocaleString('ar') + ' د.أ';
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressBar').style.background = pct >= 100 ? 'var(--success)' : pct >= 50 ? 'var(--warning)' : 'var(--danger)';
}
document.getElementById('totalAmount').addEventListener('input', updateRemaining);
document.getElementById('paidAmount').addEventListener('input', updateRemaining);
updateRemaining();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/subscriptions/create.blade.php ENDPATH**/ ?>