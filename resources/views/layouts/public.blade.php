<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Artikel Ungu Spa')</title>
  <meta name="description" content="@yield('description', 'Artikel dan panduan massage, spa, relaksasi, dan perawatan tubuh dari Ungu Spa.')">
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1')">
  <link rel="canonical" href="@yield('canonical', url()->current())">
  <meta property="og:locale" content="id_ID">
  <meta property="og:site_name" content="Ungu Spa">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('og_title', 'Artikel Ungu Spa')">
  <meta property="og:description" content="@yield('og_description', 'Artikel dan panduan perawatan tubuh dari Ungu Spa.')">
  <meta property="og:url" content="@yield('canonical', url()->current())">
  <meta property="og:image" content="@yield('og_image', asset('assets/ganbar/heroo.webp'))">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'Artikel Ungu Spa')">
  <meta name="twitter:description" content="@yield('og_description', 'Artikel dan panduan perawatan tubuh dari Ungu Spa.')">
  <meta name="twitter:image" content="@yield('og_image', asset('assets/ganbar/heroo.webp'))">
  <meta name="theme-color" content="#26003f">
  <link rel="icon" type="image/png" href="{{ asset('assets/ganbar/logo-ungu-spa-transparent.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@400;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('schema')
</head>
<body class="public-article-page">
  <header class="article-header">
    <a class="article-brand" href="{{ route('home') }}">
      <img src="{{ asset('assets/ganbar/logo-ungu-spa-transparent.png') }}" alt="Logo Ungu Spa">
      <span><strong>Ungu</strong><small>Spa</small></span>
    </a>
    <nav aria-label="Navigasi artikel">
      <a href="{{ route('home') }}">Beranda</a>
      <a href="{{ route('posts.index') }}">Artikel</a>
      <a class="article-nav-cta" href="https://wa.me/6281316879699">Booking</a>
    </nav>
  </header>
  <main>@yield('content')</main>
  <footer class="article-footer">
    <p>&copy; {{ date('Y') }} Ungu Spa. Seluruh Hak Dilindungi.</p>
    <a href="https://wa.me/6281316879699">WhatsApp: +62 813-1687-9699</a>
  </footer>
  <a class="wa-float" href="https://wa.me/6281316879699" aria-label="Chat WhatsApp"><span class="wa-ring wa-ring-one"></span><span class="wa-ring wa-ring-two"></span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.8a8.4 8.4 0 0 1-12.4 7.4L4 20.4l1.3-4a8.3 8.3 0 1 1 15.2-4.6Zm-8.4-6.6a6.6 6.6 0 0 0-5.6 10.1l.3.5-.8 2.3 2.4-.8.5.3a6.6 6.6 0 1 0 3.2-12.4Z"/></svg></a>
</body>
</html>
