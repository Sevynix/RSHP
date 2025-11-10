<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
    <div class="d-flex align-items-center">
        <i class="fas fa-align-left text-dark fs-4 me-3" style="cursor: pointer;"></i>
        <h2 class="fs-2 m-0 d-none d-md-block">@yield('page-title', 'Dashboard')</h2>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <span class="navbar-text fw-bold">
                    Welcome, {{ session('user_name', Auth::user()->nama ?? 'User') }}
                </span>
            </li>
        </ul>
    </div>
</nav>

<main class="container-fluid px-4 py-3">
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

    @yield('content')
</main>
