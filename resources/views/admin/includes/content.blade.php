<div class="content-wrapper">

    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">@yield('contentheader')</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@yield('contentheaderactive')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            @include('admin.includes.alerts.success')
            @include('admin.includes.alerts.error')
            @yield('content')
        </div>
    </div>

</div>
