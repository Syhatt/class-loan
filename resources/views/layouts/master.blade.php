<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIFASA UINSA</title>

    <link rel="icon" href="{{ asset('template/img/logo_uinsa.png') }}">

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="sidebar-brand-text mx-3">UINSA</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- SUPERADMIN --}}
            @if (auth()->user()->role === 'superadmin')
                <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="fas fa-fw fa-users-cog"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('class*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('class.index') }}">
                        <i class="fas fa-fw fa-laptop-house"></i>
                        <span>Data Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('item*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('item.index') }}">
                        <i class="fas fa-fw fa-toolbox"></i>
                        <span>Data Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('faculty*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('faculty.index') }}">
                        <i class="fas fa-fw fa-hotel"></i>
                        <span>Data Fakultas</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('booking') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('booking.index') }}">
                        <i class="fas fa-fw fa-chalkboard"></i>
                        <span>Pinjam Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybook') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybook') }}">
                        <i class="fas fa-fw fa-chalkboard-teacher"></i>
                        <span>Peminjaman Ruangan Saya</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bookingitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bookingitem.index') }}">
                        <i class="fas fa-fw fa-box-open"></i>
                        <span>Pinjam Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybookitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybookitem') }}">
                        <i class="fas fa-fw fa-box"></i>
                        <span>Peminjaman Barang Saya</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('report*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('report.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>

            {{-- ADMIN FAKULTAS --}}
            @elseif (auth()->user()->role === 'admin_fakultas')
                <li class="nav-item {{ request()->is('class*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('class.index') }}">
                        <i class="fas fa-fw fa-laptop-house"></i>
                        <span>Data Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('item*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('item.index') }}">
                        <i class="fas fa-fw fa-toolbox"></i>
                        <span>Data Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bookclass*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bookclass.index') }}">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Data Peminjaman Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bookitem*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bookitem.index') }}">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Data Peminjaman Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('booking') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('booking.index') }}">
                        <i class="fas fa-fw fa-chalkboard"></i>
                        <span>Pinjam Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybook') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybook') }}">
                        <i class="fas fa-fw fa-chalkboard-teacher"></i>
                        <span>Peminjaman Ruangan Saya</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bookingitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bookingitem.index') }}">
                        <i class="fas fa-fw fa-box-open"></i>
                        <span>Pinjam Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybookitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybookitem') }}">
                        <i class="fas fa-fw fa-box"></i>
                        <span>Peminjaman Barang Saya</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('report*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('report.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>

            {{-- USER & DOSEN --}}
            @elseif (in_array(auth()->user()->role, ['user', 'dosen']))
                <li class="nav-item {{ request()->is('booking') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('booking.index') }}">
                        <i class="fas fa-fw fa-chalkboard"></i>
                        <span>Pinjam Ruangan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybook') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybook') }}">
                        <i class="fas fa-fw fa-chalkboard-teacher"></i>
                        <span>Peminjaman Ruangan Saya</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bookingitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bookingitem.index') }}">
                        <i class="fas fa-fw fa-box-open"></i>
                        <span>Pinjam Barang</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('mybookitem') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mybookitem') }}">
                        <i class="fas fa-fw fa-box"></i>
                        <span>Peminjaman Barang Saya</span>
                    </a>
                </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                <img class="img-profile rounded-circle" src="template/img/undraw_profile.svg">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="text-center">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Room Loan 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>

    @stack('script')
</body>
</html>
