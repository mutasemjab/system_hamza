<?php $__env->startSection('title', 'لوحة التحكم'); ?>

<?php $__env->startSection('contentheader', 'لوحة التحكم'); ?>
<?php $__env->startSection('contentheaderlink', '<a href="'.route('admin.dashboard').'">الرئيسية</a>'); ?>
<?php $__env->startSection('contentheaderactive', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-indigo">
            <div class="stat-card-icon"><i class="fas fa-running"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e($totalPlayers); ?></div>
                <div class="stat-card-label">إجمالي اللاعبين</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-emerald">
            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e(number_format($totalCollected, 0)); ?></div>
                <div class="stat-card-label">المبالغ المحصّلة (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-rose">
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e(number_format($totalPending, 0)); ?></div>
                <div class="stat-card-label">مبالغ متبقية (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-amber">
            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-card-value"><?php echo e($totalUsers); ?></div>
                <div class="stat-card-label">المستخدمون</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-photo-film text-accent mr-2"></i>
                    <span class="card-title">دور المحتوى الحالي</span>
                </div>
                <a href="<?php echo e(route('admin.social.index')); ?>" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $socialSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $meta = $data['meta']; ?>
                    <div class="col-sm-6 mb-3">
                        <div class="social-dash-card" style="--sc-color: <?php echo e($meta['color']); ?>">
                            <div class="social-dash-header">
                                <i class="<?php echo e($meta['icon']); ?>" style="color:<?php echo e($meta['color']); ?>"></i>
                                <span><?php echo e($meta['label']); ?></span>
                                <span class="social-dash-counts">
                                    <?php echo e($data['pending']); ?> قادم · <?php echo e($data['published']); ?> منشور
                                </span>
                            </div>
                            <?php if($data['current']): ?>
                                <div class="social-dash-current">
                                    <div class="social-dash-avatar" style="background:linear-gradient(135deg,<?php echo e($meta['color']); ?>,#8b5cf6)">
                                        <?php echo e($data['current']->player?->initials); ?>

                                    </div>
                                    <div style="font-size:13px;font-weight:600"><?php echo e($data['current']->player?->full_name); ?></div>
                                </div>
                            <?php else: ?>
                                <div style="font-size:12px;color:var(--text-muted);padding:4px 0">لا يوجد دور محدد</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                    <span class="card-title">اشتراكات متأخرة</span>
                </div>
                <a href="<?php echo e(route('admin.subscription.index', ['status' => 'late'])); ?>" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body" style="padding:12px 22px !important">
                <?php $__empty_1 = true; $__currentLoopData = $lateSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="late-sub-row">
                    <div class="late-sub-avatar"><?php echo e($sub->player?->initials); ?></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600"><?php echo e($sub->player?->full_name); ?></div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            متبقي:
                            <strong style="color:var(--danger)"><?php echo e(number_format($sub->remaining_amount, 0)); ?> د.أ</strong>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.subscription.edit', $sub)); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="fas fa-check-circle" style="font-size:32px;color:var(--success);opacity:.5;display:block;margin-bottom:8px"></i>
                    <span style="font-size:13px;color:var(--text-muted)">لا توجد اشتراكات متأخرة 🎉</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-running text-accent mr-2"></i>
                    <span class="card-title">آخر اللاعبين المضافين</span>
                </div>
                <a href="<?php echo e(route('admin.player.index')); ?>" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body" style="padding:0 !important">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>اللاعب</th>
                                <th>الهاتف</th>
                                <th>الوزن / الطول</th>
                                <th>الاشتراك</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentPlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:10px">
                                        <?php if($player->photo_url): ?>
                                            <img src="<?php echo e($player->photo_url); ?>" style="width:36px;height:36px;border-radius:9px;object-fit:cover">
                                        <?php else: ?>
                                            <div class="dash-player-avatar"><?php echo e($player->initials); ?></div>
                                        <?php endif; ?>
                                        <div>
                                            <div style="font-size:13px;font-weight:600"><?php echo e($player->full_name); ?></div>
                                            <?php if($player->birth_date): ?>
                                                <div style="font-size:11px;color:var(--text-muted)"><?php echo e($player->age); ?> سنة</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:13px"><?php echo e($player->phone ?? '—'); ?></td>
                                <td style="font-size:13px">
                                    <?php if($player->weight || $player->height): ?>
                                        <?php echo e($player->weight ? $player->weight.'kg' : ''); ?>

                                        <?php echo e($player->weight && $player->height ? '/' : ''); ?>

                                        <?php echo e($player->height ? $player->height.'cm' : ''); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($player->subscription): ?>
                                        <?php $badge = $player->subscription->status_badge; ?>
                                        <span class="badge <?php echo e($badge['class']); ?>"><?php echo e($badge['label']); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">لا يوجد</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.player.edit', $player)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">لا يوجد لاعبون بعد</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.social-dash-card {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 14px;
    background: #fff;
    transition: box-shadow .2s;
}
.social-dash-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.social-dash-header {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600; margin-bottom: 10px;
}
.social-dash-counts { font-size: 11px; color: var(--text-muted); font-weight: 400; margin-left: auto; }
.social-dash-current {
    display: flex; align-items: center; gap: 8px;
    background: #f8fafc; border-radius: 8px; padding: 8px 10px;
}
.social-dash-avatar {
    width: 30px; height: 30px; border-radius: 8px;
    color: #fff; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.late-sub-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f1f5f9;
}
.late-sub-row:last-child { border-bottom: none; }
.late-sub-avatar {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dash-player-avatar {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>