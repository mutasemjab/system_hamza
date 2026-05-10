<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left: toggle + breadcrumb hint -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link sidebar-toggle-btn" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-md-flex align-items-center ms-2">
            <span class="nav-brand-label">
                <i class="fas fa-layer-group me-1"></i> Sys
            </span>
        </li>
    </ul>

    <!-- Right controls -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Language switcher -->
        <li class="nav-item dropdown lang-dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fas fa-globe"></i>
                <span class="d-none d-sm-inline ml-1">{{ strtoupper(app()->getLocale()) }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item {{ app()->getLocale() === $localeCode ? 'active' : '' }}"
                   hreflang="{{ $localeCode }}"
                   href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    <span class="flag-icon mr-2">{{ $properties['native'] }}</span>
                </a>
                @endforeach
            </div>
        </li>

        <!-- Divider -->
        <li class="nav-item"><span class="nav-divider"></span></li>

        <!-- User dropdown -->
        <li class="nav-item dropdown user-dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                <div class="user-avatar-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline ml-2 user-name-nav">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down ml-1 small"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <div class="dropdown-header user-dropdown-header">
                    <div class="user-avatar-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="mt-2">
                        <strong>{{ auth()->user()->name }}</strong>
                        <div class="small text-muted">{{ auth()->user()->email ?? 'Administrator' }}</div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.login.edit', auth()->user()->id) }}">
                    <i class="fas fa-user-cog mr-2 text-primary"></i> {{ __('messages.Account_Settings') }}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('messages.Logout') }}
                </a>
            </div>
        </li>
    </ul>
</nav>
