
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RSHP - Rumah Sakit Hewan Pendidikan Unair</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/public-layout.css') }}">
    @stack('styles')
</head>
<body>
    <header>
        <h1>Rumah Sakit Hewan Pendidikan (RSHP) Unair</h1>
        <p>Memberikan pelayanan terbaik untuk kesehatan hewan Anda</p>
    </header>
    <nav>
        <a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a>
        <a href="{{ url('/struktur') }}"><i class="fas fa-sitemap"></i> Struktur Organisasi</a>
        <a href="{{ url('/layanan') }}"><i class="fas fa-stethoscope"></i> Layanan Umum</a>
        <a href="{{ url('/visimisi') }}"><i class="fas fa-bullseye"></i> Visi & Misi</a>
        <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
        @auth
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
        @endauth
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} RSHP Universitas Airlangga</p>
    </footer>
</body>
</html>
