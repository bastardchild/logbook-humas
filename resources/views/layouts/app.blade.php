<!doctype html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Logbook Humas')</title>
    <link rel="icon" href="{{ asset('unmer.png') }}" type="image/x-icon">


    <!-- Pico CSS (CDN) -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.orange.min.css" > 
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- optional small custom style -->
    <style>
      /* agar tabel rapih di mobile */
      :root {  
        --pico-font-size: 0.9375rem; 
        }
      .table-responsive { overflow-x:auto; }
      .badge { font-size: .8rem; }
      @media (min-width: 1536px) {
            :host, :root {
                --pico-font-size: 100%;
            }
        }
        @media (min-width: 1280px) {
            :host, :root {
                --pico-font-size: 100%;
            }
        }
        @media (min-width: 1024px) {
            :host, :root {
                --pico-font-size: 100%;
            }
        }
        @media (min-width: 768px) {
            :host, :root {
                --pico-font-size: 100%;
            }
        }
        @media (min-width: 576px) {
            :host, :root {
                --pico-font-size: 100%;
            }
        }
    </style>
    @stack('styles')
</head>

<body id="app">
<header class="container-fluid">
  <nav class="container">
    <ul>
        <li><a href="{{ route('kegiatan.index') }}">Daftar Kegiatan</a></li>
    @if(session('editor_auth'))
        <li><a href="{{ route('kegiatan.create') }}">Tambah Kegiatan</a></li>
        <li><a role="button" href="{{ route('editor.logout') }}">Logout</a></li>
    @else
        <li><a role="button" href="{{ route('editor.login') }}">Login</a></li>
    @endif
    </ul>

    <ul>
        <li>
            <label for="themeSwitch">
                <input type="checkbox" id="themeSwitch" role="switch">
                <span>Dark mode</span>
            </label>
        </li>
    </ul>
  </nav>
</header>

<main class="container">
    @if(session('success'))
      <section class="notice success">
        {{ session('success') }}
      </section>
    @endif

    @yield('content')
</main>

<footer class="container">
  <small>&copy; {{ date('Y') }} Humas — Sistem Logbook</small>
</footer>

<script>
    const themeSwitch = document.getElementById('themeSwitch');

    // DEFAULT DARK
    const savedTheme = localStorage.getItem('theme') ?? 'dark';

    document.documentElement.setAttribute('data-theme', savedTheme);
    themeSwitch.checked = savedTheme === 'dark';

    themeSwitch.addEventListener('change', () => {
        const theme = themeSwitch.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    });
</script>


<script>
function hapusKegiatan(id) {
    if (!confirm('Yakin ingin menghapus kegiatan ini?')) return;

    fetch(`{{ url('/kegiatan') }}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (res.ok) {
            location.reload();
        } else {
            alert('Gagal menghapus data');
        }
    });
}
</script>

@stack('scripts')
</body>
</html>
