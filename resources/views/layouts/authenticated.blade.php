<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - RSHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- RSHP Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        {{-- Collapsible Sidebar --}}
        <aside id="sidebar-wrapper" class="collapsible-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="{{ asset('images/logo-unair.png') }}" alt="Unair" style="width: 40px; height: 40px; border-radius: 50%;">
                    <h1 class="sidebar-title">RSHP</h1>
                </div>
            </div>
            
            {{-- Dynamic Sidebar Menu --}}
            <ul class="sidebar-nav">
                @yield('sidebar-menu')
            </ul>
            
            {{-- Sidebar Footer with User Info --}}
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-info">
                        <span class="user-name">{{ session('nama', 'User') }}</span>
                        <span class="user-role">@yield('user-role', 'User')</span>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="logout-icon" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>
        
        {{-- Page Content Wrapper --}}
        <div id="page-content-wrapper">
            {{-- Top Navigation Bar --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left text-dark fs-4 me-3" style="cursor: pointer;"></i>
                    <h2 class="fs-2 m-0 d-none d-md-block">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <span class="navbar-text fw-bold">
                                Welcome, {{ session('nama', 'User') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </nav>
            
            {{-- Main Content Area --}}
            <main class="container-fluid px-4 py-3">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Page Content --}}
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
