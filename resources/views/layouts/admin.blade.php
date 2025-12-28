<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Mitra10</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('build/assets/jquery.min.js') }}"></script>
    <script>
        if (typeof moment === 'undefined') {
            var momentScript = document.createElement('script');
            momentScript.src = "{{ asset('build/assets/moment-with-locales.min.js') }}";
            document.head.appendChild(momentScript);
        }
    </script>
    <script src="{{ asset('build/assets/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('build/assets/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('build/assets/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('build/assets/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('build/assets/adminlte.min.js') }}"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                        <span class="d-none d-md-inline ml-1">{{ Auth::user()->name ?? 'Tamu' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">Pengaturan Pengguna</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Logo AdminLTE" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SPK Mitra10</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="Gambar Pengguna">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name ?? 'Tamu' }}</a>
                    </div>
                </div>

                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Cari" aria-label="Cari">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        @auth
                        @if(Auth::user()->hasRole('Admin'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-database"></i>
                                <p>
                                    Data Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('criteria.index') }}" class="nav-link {{ Request::routeIs('criteria.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kriteria</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('products.index') }}" class="nav-link {{ Request::routeIs('products.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Produk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('stores.index') }}" class="nav-link {{ Request::routeIs('stores.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Toko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('raw-materials.index') }}" class="nav-link {{ Request::routeIs('raw-materials.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bahan Baku</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->hasRole('Admin'))
                        <li class="nav-item">
                            <a href="{{ route('setting-costs.index') }}" class="nav-link {{ Request::routeIs('setting-costs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>Pengaturan Biaya</p>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->hasAnyRole(['Admin', 'Manajer Produksi', 'Staf']))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-calculator"></i>
                                <p>
                                    Modul SPK
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('electre.product.index') }}" class="nav-link {{ request()->routeIs('electre.product.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>ELECTRE Produk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('electre.store.index') }}" class="nav-link {{ request()->routeIs('electre.store.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>ELECTRE Toko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                     <a href="{{ route('production-schedule.index') }}" class="nav-link {{ request()->routeIs('production-schedule.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Jadwal Produksi</p>
                                        </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('eoq.index') }}" class="nav-link {{ Request::routeIs('eoq.index', 'eoq.calculator', 'eoq.results') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Kalkulator EOQ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-pdf"></i>
                                <p>
                                    Laporan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('electre.product.history') }}" class="nav-link {{ Request::routeIs('electre.product.history') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Riwayat Electre Product</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('electre.store.history') }}" class="nav-link {{ Request::routeIs('electre.store.history') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Riwayat Electre Toko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reports.jadwal-produksi') }}" class="nav-link">
                                        <i class="fas fa-calendar-alt nav-icon"></i>
                                        <p>Jadwal Produksi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('eoq.history') }}" class="nav-link {{ Request::routeIs('eoq.history') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Riwayat EOQ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endauth
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Judul</h5>
                <p></p>
            </div>
        </aside>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
            </div>
            <strong>SPK Mitra10</strong> 
        </footer>
    </div>
    @push("scripts")
    <script>
        $(document).ready(function() {
            $('[data-widget="treeview"]').Treeview('init');
            $('[data-widget="pushmenu"]').PushMenu();
            $('[data-widget="sidebar-search"]').SidebarSearch();
        });
    </script>
    @endpush
    @stack('scripts')
</body>

</html>