<!-- filepath: /c:/Laravel/ts11/chocopie/resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Web Đọc Truyện</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            padding: 0;
            margin: 0;
        }
        .navbar-dark .navbar-nav .nav-link,
        .navbar-dark .navbar-brand {
            color: rgba(255, 255, 255, .9);
        }
        
        /* Layout structure */
        .main-wrapper {
            display: flex;
            width: 100%;
            position: relative;
        }
        
        /* Fixed sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            z-index: 100;
            background-color: #f8f9fa;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        
        /* Content area */
        .content-area {
            width: 100%;
            margin-left: 250px;
            padding: 20px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
        }
        
        /* Fluid container */
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }
            
            .content-area {
                margin-left: 0;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.7);
                z-index: 99;
                opacity: 0;
                transition: all 0.5s ease-in-out;
                top: 0;
                left: 0;
            }
            
            .overlay.active {
                display: block;
                opacity: 1;
            }
        }
        
        /* Navigation styling */
        .sidebar .nav-link {
            padding: 10px 20px;
            color: #495057;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        
        /* Card styles */
        .card {
            width: 100% !important;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        
        /* Utilities */
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
        
        .card-stats {
            transition: transform .3s;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .welcome-section {
            padding: 30px 0;
        }
        
        @yield('styles')
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <button type="button" id="sidebarCollapse" class="btn btn-dark d-md-none">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"> <i class="fas fa-user"></i> Hồ sơ</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main wrapper with sidebar and content -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-bar"></i> Thống kê
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.stories*') && !request()->routeIs('admin.stories.pending') ? 'active' : '' }}" href="{{ route('admin.stories') }}">
                        <i class="fas fa-book"></i> Quản Lý Truyện
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.stories.pending') ? 'active' : '' }}" href="{{ route('admin.stories.pending') }}">
                        <i class="fas fa-clock"></i> 
                        Truyện Chờ Duyệt
                        @php
                            $pendingCount = App\Models\Story::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge badge-danger">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="fas fa-users"></i> Quản Lý Người Dùng
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Content area -->
        <div class="content-area">
            @yield('content')
        </div>
        
        <!-- Overlay for sidebar (mobile) -->
        <div class="overlay"></div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mobile sidebar toggle
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('.overlay').toggleClass('active');
            });
            
            // Close sidebar when clicking outside or on overlay (mobile only)
            $('.overlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });
            
            // Handle responsive behavior
            $(window).resize(function() {
                if ($(window).width() > 768) {
                    $('#sidebar').removeClass('active');
                    $('.overlay').removeClass('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>