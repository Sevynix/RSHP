<aside id="sidebar-wrapper" class="collapsible-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-unair.png') }}" alt="Unair" style="width: 40px; height: 40px; border-radius: 50%;">
            <h1 class="sidebar-title">RSHP</h1>
        </div>
    </div>
    
    <ul class="sidebar-nav">
        @yield('sidebar-menu')
    </ul>
    
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">{{ session('user_name', Auth::user()->nama ?? 'User') }}</span>
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
