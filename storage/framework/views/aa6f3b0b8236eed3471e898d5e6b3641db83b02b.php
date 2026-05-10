<?php $__env->startSection('title', 'اللاعبون'); ?>

<?php $__env->startSection('contentheader', 'إدارة اللاعبين'); ?>
<?php $__env->startSection('contentheaderactive', 'اللاعبون'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted" style="font-size:13px">
                إجمالي اللاعبين: <strong><?php echo e($players->total()); ?></strong>
            </span>
        </div>
        <a href="<?php echo e(route('players.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> إضافة لاعب
        </a>
    </div>
</div>


<div class="card mb-3">
    <div class="card-body" style="padding:14px 22px !important">
        <form method="GET" action="<?php echo e(route('players.index')); ?>" class="d-flex gap-2" style="gap:10px">
            <input type="text" name="search" class="form-control" style="max-width:320px"
                   placeholder="بحث بالاسم أو الهاتف..." value="<?php echo e(request('search')); ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> بحث
            </button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('players.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times mr-1"></i> إلغاء
            </a>
            <?php endif; ?>
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
                        <th>تاريخ الميلاد / العمر</th>
                        <th>الهاتف</th>
                        <th>الوزن / الطول</th>
                        <th>الاشتراك</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted" style="font-size:12px"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:12px">
                                <?php if($player->photo_url): ?>
                                    <img src="<?php echo e($player->photo_url); ?>" alt="<?php echo e($player->full_name); ?>"
                                         style="width:42px;height:42px;border-radius:10px;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,.1)">
                                <?php else: ?>
                                    <div class="player-avatar"><?php echo e($player->initials); ?></div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight:600;font-size:14px"><?php echo e($player->full_name); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($player->birth_date): ?>
                                <div style="font-size:13px"><?php echo e($player->birth_date->format('Y/m/d')); ?></div>
                                <div class="text-muted" style="font-size:12px"><?php echo e($player->age); ?> سنة</div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($player->phone): ?>
                                <a href="tel:<?php echo e($player->phone); ?>" style="font-size:13px;text-decoration:none;color:var(--text-primary)">
                                    <i class="fas fa-phone text-muted mr-1" style="font-size:11px"></i>
                                    <?php echo e($player->phone); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-size:13px">
                                <?php if($player->weight): ?>
                                    <span class="badge badge-secondary mr-1"><?php echo e($player->weight); ?> kg</span>
                                <?php endif; ?>
                                <?php if($player->height): ?>
                                    <span class="badge badge-secondary"><?php echo e($player->height); ?> cm</span>
                                <?php endif; ?>
                                <?php if(!$player->weight && !$player->height): ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if($player->subscription): ?>
                                <?php $badge = $player->subscription->status_badge; ?>
                                <span class="badge <?php echo e($badge['class']); ?>"><?php echo e($badge['label']); ?></span>
                                <div class="text-muted mt-1" style="font-size:11px">
                                    متبقي: <?php echo e(number_format($player->subscription->remaining_amount, 0)); ?> د.أ
                                </div>
                            <?php else: ?>
                                <span class="badge badge-secondary">لا يوجد</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex" style="gap:6px">
                                <a href="<?php echo e(route('players.edit', $player)); ?>" class="btn btn-sm btn-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('players.destroy', $player)); ?>"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا اللاعب؟')">
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
                        <td colspan="7" class="text-center py-5">
                            <div style="color:var(--text-muted)">
                                <i class="fas fa-users" style="font-size:36px;opacity:.3;display:block;margin-bottom:10px"></i>
                                لا يوجد لاعبون بعد
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($players->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($players->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.player-avatar {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/players/index.blade.php ENDPATH**/ ?>