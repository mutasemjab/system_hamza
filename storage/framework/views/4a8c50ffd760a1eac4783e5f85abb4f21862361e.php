<?php $__env->startSection('title', 'السوشال ميديا'); ?>

<?php $__env->startSection('contentheader', 'إدارة محتوى السوشال ميديا'); ?>
<?php $__env->startSection('contentheaderactive', 'السوشال ميديا'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:13px">
        <i class="fas fa-info-circle mr-1"></i>
        تتبع دور كل لاعب في كل نوع من أنواع المحتوى
    </p>
    <a href="<?php echo e(route('social.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i> إضافة لاعب للقائمة
    </a>
</div>


<div class="social-board">
    <?php $__currentLoopData = $board; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $meta      = $data['meta'];
        $current   = $data['current'];
        $queue     = $data['queue'];
        $published = $data['published'];
    ?>
    <div class="social-col">

        
        <div class="social-col-header" style="--col-color: <?php echo e($meta['color']); ?>">
            <div class="social-col-icon">
                <i class="<?php echo e($meta['icon']); ?>"></i>
            </div>
            <div>
                <div class="social-col-title"><?php echo e($meta['label']); ?></div>
                <div class="social-col-count">
                    <?php echo e($queue->count()); ?> في الانتظار · <?php echo e($published->count()); ?> منشور
                </div>
            </div>
        </div>

        
        <div class="social-section-label">
            <i class="fas fa-star mr-1" style="color:var(--warning)"></i> دوره الآن
        </div>
        <?php if($current): ?>
        <div class="social-current-card">
            <div class="social-player-row">
                <div class="social-avatar" style="background:linear-gradient(135deg,<?php echo e($meta['color']); ?>,#8b5cf6)">
                    <?php echo e($current->player?->initials); ?>

                </div>
                <div class="flex-1">
                    <div class="social-player-name"><?php echo e($current->player?->full_name); ?></div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        <?php echo e($current->created_at->diffForHumans()); ?>

                    </div>
                </div>
            </div>
            <div class="d-flex mt-3" style="gap:6px">
                <form method="POST" action="<?php echo e(route('social.markPublished', $current)); ?>" style="flex:1">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-check mr-1"></i> تم النشر
                    </button>
                </form>
                <a href="<?php echo e(route('social.edit', $current)); ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="<?php echo e(route('social.destroy', $current)); ?>"
                      onsubmit="return confirm('حذف هذا السجل؟')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="social-empty-slot">
            <i class="fas fa-user-clock" style="font-size:20px;opacity:.3"></i>
            <div style="font-size:12px;margin-top:6px">لا يوجد دور محدد</div>
        </div>
        <?php endif; ?>

        
        <?php if($queue->count()): ?>
        <div class="social-section-label mt-3">
            <i class="fas fa-list-ol mr-1" style="color:var(--accent)"></i> قائمة الانتظار
        </div>
        <div class="social-queue">
            <?php $__currentLoopData = $queue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="social-queue-item">
                <span class="social-queue-num"><?php echo e($i + 1); ?></span>
                <div class="social-avatar-xs" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                    <?php echo e($item->player?->initials); ?>

                </div>
                <span class="flex-1" style="font-size:13px;font-weight:500"><?php echo e($item->player?->full_name); ?></span>
                <div class="d-flex" style="gap:4px">
                    <a href="<?php echo e(route('social.edit', $item)); ?>" class="social-queue-action" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="<?php echo e(route('social.destroy', $item)); ?>"
                          onsubmit="return confirm('حذف؟')" style="margin:0">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="social-queue-action text-danger" title="حذف">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <?php if($published->count()): ?>
        <div class="social-section-label mt-3">
            <i class="fas fa-check-double mr-1" style="color:var(--success)"></i> آخر منشور
        </div>
        <div class="social-published">
            <?php $__currentLoopData = $published; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="social-published-item">
                <div class="social-avatar-xs" style="background:#e2e8f0;color:var(--text-muted)">
                    <?php echo e($item->player?->initials); ?>

                </div>
                <span style="font-size:12px;color:var(--text-muted);flex:1"><?php echo e($item->player?->full_name); ?></span>
                <span style="font-size:11px;color:var(--success)">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?php echo e($item->published_at ? $item->published_at->format('m/d') : '—'); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Board Layout */
.social-board {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    align-items: start;
}
@media (max-width: 1199px) { .social-board { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px)  { .social-board { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .social-board { grid-template-columns: 1fr; } }

.social-col {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Column header */
.social-col-header {
    padding: 16px;
    background: linear-gradient(135deg, var(--col-color, #6366f1), color-mix(in srgb, var(--col-color, #6366f1) 70%, #000));
    display: flex;
    align-items: center;
    gap: 12px;
}
.social-col-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 18px; flex-shrink: 0;
}
.social-col-title {
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.2;
}
.social-col-count {
    color: rgba(255,255,255,.75);
    font-size: 11px;
    margin-top: 2px;
}

/* Section labels */
.social-section-label {
    padding: 10px 14px 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: var(--text-muted);
}

/* Current turn card */
.social-current-card {
    margin: 0 12px 4px;
    padding: 14px;
    background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(139,92,246,.04));
    border: 1px solid rgba(99,102,241,.2);
    border-radius: 12px;
}
.social-player-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.social-avatar {
    width: 40px; height: 40px;
    border-radius: 10px;
    color: #fff; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}
.social-avatar-xs {
    width: 28px; height: 28px;
    border-radius: 7px;
    color: #fff; font-size: 10px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.social-player-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }

/* Empty slot */
.social-empty-slot {
    margin: 0 12px 4px;
    padding: 20px;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    text-align: center;
    color: var(--text-muted);
}

/* Queue */
.social-queue {
    padding: 0 12px 4px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.social-queue-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    background: #f8fafc;
    border-radius: 8px;
    transition: background .15s;
}
.social-queue-item:hover { background: #f0f4ff; }
.social-queue-num {
    width: 20px; height: 20px;
    border-radius: 6px;
    background: var(--border-color);
    color: var(--text-muted);
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.social-queue-action {
    width: 26px; height: 26px;
    border-radius: 6px;
    background: none; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-muted);
    font-size: 11px;
    transition: background .15s, color .15s;
}
.social-queue-action:hover { background: #e2e8f0; color: var(--text-primary); }

/* Published */
.social-published {
    padding: 0 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.social-published-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
    border-bottom: 1px solid #f1f5f9;
}
.social-published-item:last-child { border-bottom: none; }
.flex-1 { flex: 1; min-width: 0; }
.w-100 { width: 100% !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/social/index.blade.php ENDPATH**/ ?>