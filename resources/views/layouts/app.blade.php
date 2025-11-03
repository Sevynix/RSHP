
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RSHP - Rumah Sakit Hewan Pendidikan Unair</title>
    <style>
        :root { --nav-height: 64px; }
        html { scroll-padding-top: calc(var(--nav-height) + 1rem); }
        body { font-family: Arial, sans-serif; margin:0; padding:0; line-height:1.6; color:#333; }
        header { background: linear-gradient(135deg,#0073e6,#0056b3); color:#fff; text-align:center; padding:2rem 0; }
        header h1{ margin:0; font-size:2.5rem }
        header p{ margin:.5rem 0 0 0; font-size:1.1rem }
    nav { background:#f8f9fa; padding:.75rem 1rem; text-align:center; box-shadow:0 2px 5px rgba(0,0,0,.1); position:sticky; top:0; z-index:1000; height:var(--nav-height); display:flex; align-items:center; justify-content:center }
        nav a{ color:#0073e6; text-decoration:none; margin:0 1rem; padding:.5rem 1rem; border-radius:5px; transition:background .3s }
        nav a:hover{ background:#0073e6; color:#fff }
        .btn-logout{ color:#dc3545; background:none; border:none; cursor:pointer; padding:.5rem 1rem; border-radius:5px; transition:background .3s; font:inherit; margin:0 1rem; text-decoration:none; }
        .btn-logout:hover{ background:#dc3545; color:#fff }
        .user-info{ color:#333; margin:0 1rem; font-weight:500; }
        section{ max-width:1200px; margin:0 auto; padding:2rem }
        section h2{ color:#0073e6; border-bottom:2px solid #0073e6; padding-bottom:.5rem; margin-bottom:1.5rem }
        .struktur{ display:block; text-align:center }
        .level{ margin-bottom:2rem; display:flex; justify-content:center; gap:2rem; flex-wrap:wrap }
        .isikonten{ text-align:center; background:#fff; padding:1.5rem; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,.1); max-width:300px; min-width:250px }
        .isikonten img{ width:120px; height:120px; border-radius:50%; object-fit:cover; margin:1rem 0 }
        .jabatan{ font-weight:bold; color:#0073e6; margin-bottom:.5rem }
        .name{ font-weight:500; color:#333 }
        .layanan{ width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,.1) }
        .layanan th{ background:#0073e6; color:#fff; padding:1rem; text-align:center }
        .layanan td{ padding:1rem; border-bottom:1px solid #eee }
        .layanan tr:hover{ background:#f8f9fa }
        #visi{ background:#f8f9fa; border-radius:10px }
        footer{ background:#333; color:#fff; text-align:center; padding:1rem; margin-top:2rem }
    </style>
</head>
<body>
    <header>
        <h1>Rumah Sakit Hewan Pendidikan (RSHP) Unair</h1>
        <p><i>Memberikan pelayanan terbaik untuk kesehatan hewan Anda</i></p>
    </header>
    <nav>
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/struktur') }}">Struktur Organisasi</a>
        <a href="{{ url('/layanan') }}">Layanan Umum</a>
        <a href="{{ url('/visimisi') }}">Visi & Misi</a>
        <a href="{{ route('login') }}">Login</a>
        @auth
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
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
