<aside class="main-sidebar elevation-0">

    <!-- Brand -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
        <div class="brand-icon-wrap">
            <i class="fas fa-layer-group"></i>
        </div>
        <span class="brand-text">
            Sys <span class="brand-sub">Admin</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel-custom d-flex align-items-center">
            <div class="user-avatar-sidebar">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
            <div class="user-panel-info <?php echo e(app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3'); ?>">
                <div class="user-panel-name"><?php echo e(auth()->user()->name); ?></div>
                <div class="user-panel-role">
                    <span class="status-dot"></span> <?php echo e(__('messages.Online')); ?>

                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                       class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p><?php echo e(__('messages.Dashboard')); ?></p>
                    </a>
                </li>

                <!-- Academy Section -->
                <li class="nav-header"><?php echo e(__('messages.Academy_Management')); ?></li>

                <li class="nav-item">
                    <a href="<?php echo e(route('players.index')); ?>"
                       class="nav-link <?php echo e(request()->routeIs('players.*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-running"></i>
                        <p><?php echo e(__('messages.Players')); ?></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo e(route('subscriptions.index')); ?>"
                       class="nav-link <?php echo e(request()->routeIs('subscriptions.*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p><?php echo e(__('messages.Subscriptions')); ?></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo e(route('social.index')); ?>"
                       class="nav-link <?php echo e(request()->routeIs('social.*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-photo-film"></i>
                        <p><?php echo e(__('messages.Social_Media')); ?></p>
                    </a>
                </li>

                <!-- Administration Section -->
                <li class="nav-header"><?php echo e(__('messages.Administration')); ?></li>

                <li class="nav-item">
                    <a href="<?php echo e(route('admin.login.edit', auth()->user()->id)); ?>"
                       class="nav-link <?php echo e(request()->routeIs('admin.login.edit') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p><?php echo e(__('messages.Account_Settings')); ?></p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH C:\xampp\htdocs\sys\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>