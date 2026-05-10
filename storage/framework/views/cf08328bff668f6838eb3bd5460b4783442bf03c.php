<?php $__env->startSection('title', 'الاشتراكات'); ?>

<?php $__env->startSection('contentheader', 'إدارة الاشتراكات'); ?>
<?php $__env->startSection('contentheaderactive', 'الاشتراكات'); ?>

<?php $__env->startSection('content'); ?>


<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-indigo">
            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e(number_format($totalCollected, 0)); ?></div>
                <div class="stat-card-label">إجمالي المحصّل (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-rose">
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e(number_format($totalPending, 0)); ?></div>
                <div class="stat-card-label">المبالغ المتبقية (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-emerald">
            <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e($activeCount); ?></div>
                <div class="stat-card-label">اشتراكات فعالة</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-amber">
            <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e($lateCount); ?></div>
                <div class="stat-card-label">اشتراكات متأخرة</div>
            </div>
        </div>
    </div>
</div>


<?php if($alerts->count()): ?>
<div class="card mb-3" style="border-left: 4px solid var(--warning) !important;">
    <div class="card-body" style="padding:16px 22px !important">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-bell text-warning mr-2" style="font-size:18px"></i>
            <strong style="font-size:14px">تنبيهات الدفعات المستحقة</strong>
        </div>
        <div class="row">
            <?php $__currentLoopData = $alerts->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-2">
                <div class="d-flex align-items-center" style="gap:10px;background:#fffbeb;border-radius:8px;padding:10px 12px">
                    <div class="player-avatar-sm"><?php echo e($alert->player?->initials); ?></div>
                    <div>
                        <div style="font-size:13px;font-weight:600"><?php echo e($alert->player?->full_name); ?></div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            متبقي: <strong style="color:var(--danger)"><?php echo e(number_format($alert->remaining_amount, 0)); ?> د.أ</strong>
                            <?php if($alert->is_expiring_soon): ?>
                                &nbsp;· <span style="color:var(--warning)">ينتهي قريباً</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="card mb-3">
    <div class="card-body" style="padding:14px 22px !important">
        <form method="GET" action="<?php echo e(route('subscriptions.index')); ?>" class="d-flex flex-wrap" style="gap:10px;align-items:center">
            <input type="text" name="search" class="form-control" style="max-width:260px"
                   placeholder="بحث باسم اللاعب..." value="<?php echo e(request('search')); ?>">
            <select name="status" class="form-control no-select2" style="max-width:180px">
                <option value="">جميع الحالات</option>
                <option value="active"  <?php echo e(request('status') == 'active'  ? 'selected' : ''); ?>>فعال</option>
                <option value="late"    <?php echo e(request('status') == 'late'    ? 'selected' : ''); ?>>متأخر</option>
                <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>منتهي</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> بحث
            </button>
            <?php if(request()->hasAny(['search','status'])): ?>
            <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times mr-1"></i> إلغاء
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('subscriptions.create')); ?>" class="btn btn-primary ml-auto">
                <i class="fas fa-plus mr-1"></i> إضافة اشتراك
            </a>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-body" style="padding:0 !important">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اللاعب</th>
                        <th>إجمالي الاشتراك</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>نسبة السداد</th>
                        <th>آخر دفعة</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted" style="font-size:12px"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:10px">
                                <div class="player-avatar-sm"><?php echo e($sub->player?->initials); ?></div>
                                <div style="font-weight:600;font-size:13px"><?php echo e($sub->player?->full_name); ?></div>
                            </div>
                        </td>
                        <td style="font-weight:600"><?php echo e(number_format($sub->total_amount, 0)); ?> <small class="text-muted">د.أ</small></td>
                        <td class="text-success" style="font-weight:600"><?php echo e(number_format($sub->paid_amount, 0)); ?> <small class="text-muted">د.أ</small></td>
                        <td style="font-weight:600;color:<?php echo e($sub->remaining_amount > 0 ? 'var(--danger)' : 'var(--success)'); ?>">
                            <?php echo e(number_format($sub->remaining_amount, 0)); ?> <small class="text-muted">د.أ</small>
                        </td>
                        <td style="min-width:130px">
                            <div class="d-flex align-items-center" style="gap:8px">
                                <div style="flex:1;background:#e2e8f0;border-radius:99px;height:6px;overflow:hidden">
                                    <div style="height:100%;width:<?php echo e($sub->payment_percent); ?>%;border-radius:99px;background:<?php echo e($sub->payment_percent >= 100 ? 'var(--success)' : ($sub->payment_percent >= 50 ? 'var(--warning)' : 'var(--danger)')); ?>"></div>
                                </div>
                                <span style="font-size:12px;font-weight:600;min-width:35px"><?php echo e($sub->payment_percent); ?>%</span>
                            </div>
                        </td>
                        <td style="font-size:13px">
                            <?php echo e($sub->last_payment_date ? $sub->last_payment_date->format('Y/m/d') : '—'); ?>

                        </td>
                        <td style="font-size:13px">
                            <?php if($sub->end_date): ?>
                                <span style="<?php echo e($sub->is_expiring_soon ? 'color:var(--warning);font-weight:600' : ''); ?>">
                                    <?php echo e($sub->end_date->format('Y/m/d')); ?>

                                </span>
                                <?php if($sub->is_expiring_soon): ?>
                                    <i class="fas fa-exclamation-circle text-warning ml-1" title="ينتهي قريباً"></i>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $badge = $sub->status_badge; ?>
                            <span class="badge <?php echo e($badge['class']); ?>"><?php echo e($badge['label']); ?></span>
                        </td>
                        <td>
                            <div class="d-flex" style="gap:6px">
                                <a href="<?php echo e(route('subscriptions.edit', $sub)); ?>" class="btn btn-sm btn-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('subscriptions.destroy', $sub)); ?>"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-file-invoice-dollar" style="font-size:36px;opacity:.3;display:block;margin-bottom:10px;color:var(--text-muted)"></i>
                            <span class="text-muted">لا توجد اشتراكات بعد</span>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($subscriptions->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($subscriptions->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.player-avatar-sm {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/subscriptions/index.blade.php ENDPATH**/ ?>