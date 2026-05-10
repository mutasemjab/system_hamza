<aside class="main-sidebar elevation-0">

    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
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
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-panel-info {{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                <div class="user-panel-name">{{ auth()->user()->name }}</div>
                <div class="user-panel-role">
                    <span class="status-dot"></span> {{ __('messages.Online') }}
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>{{ __('messages.Dashboard') }}</p>
                    </a>
                </li>

                <!-- Academy Section -->
                <li class="nav-header">{{ __('messages.Academy_Management') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.player.index') }}"
                       class="nav-link {{ request()->routeIs('admin.player.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-running"></i>
                        <p>{{ __('messages.Players') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subscription.index') }}"
                       class="nav-link {{ request()->routeIs('admin.subscription.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('messages.Subscriptions') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.social.index') }}"
                       class="nav-link {{ request()->routeIs('admin.social.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-photo-film"></i>
                        <p>{{ __('messages.Social_Media') }}</p>
                    </a>
                </li>

                <!-- Administration Section -->
                <li class="nav-header">{{ __('messages.Administration') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.login.edit', auth()->user()->id) }}"
                       class="nav-link {{ request()->routeIs('admin.login.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>{{ __('messages.Account_Settings') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
