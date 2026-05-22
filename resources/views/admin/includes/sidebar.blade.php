<aside class="main-sidebar elevation-0">

    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/admin/logo.png') }}" alt="Logo" class="brand-logo">
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
                    <a href="{{ route('players.index') }}"
                       class="nav-link {{ request()->routeIs('players.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-running"></i>
                        <p>{{ __('messages.Players') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('subscriptions.index') }}"
                       class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('messages.Subscriptions') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('social.index') }}"
                       class="nav-link {{ request()->routeIs('social.*') ? 'active' : '' }}">
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
