<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
  {{-- =========================================================
       1. LOGIK PHP: AMBIL DATA DARI SITE SETTINGS (DATABASE)
       ========================================================= --}}
  @php
      use App\Models\Site;
      
      // Ambil data setting pertama
      $siteSettings = Site::first();

      // Default Fallback (Jika database belum diisi)
      $defaultTitle = 'Portfolio — Home 02 Style';
      $defaultFav   = asset('assets/template/images/favicon.svg');

      // Assign Variabel
      $dbSiteName = $siteSettings->site_name ?? $defaultTitle;
      $dbDesc     = $siteSettings->site_description ?? 'Professional Portfolio Website';
      $dbKeys     = $siteSettings->keywords ?? 'portfolio, design, development';
      
      // Logic Favicon: Kalau ada link di DB pakai itu, kalau tidak pakai default assets
      $finalFav   = !empty($siteSettings->favicon_url) ? $siteSettings->favicon_url : $defaultFav;

      // Logic Open Graph (Social Share)
      $ogTitle    = $siteSettings->og_title ?? $dbSiteName;
      $ogDesc     = $siteSettings->og_description ?? $dbDesc;
      $ogImage    = $siteSettings->og_image_url ?? '';
  @endphp

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <meta name="theme-color" content="#000000">
  <meta name="msapplication-navbutton-color" content="#000000">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  {{-- =========================================================
       2. SEO & META TAGS DINAMIS
       ========================================================= --}}
  {{-- Judul: Prioritas $title (dari page), lalu DB, lalu default --}}
  <title>{{ $title ?? $dbSiteName }}</title>
  
  <meta name="description" content="{{ $dbDesc }}">
  <meta name="keywords" content="{{ $dbKeys }}">

  {{-- Open Graph (Agar link cantik saat disebar di WA/IG) --}}
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $ogTitle }}">
  <meta property="og:description" content="{{ $ogDesc }}">
  @if(!empty($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
  @endif

  {{-- =========================================================
       3. FAVICON DINAMIS
       ========================================================= --}}
  <link rel="shortcut icon" href="{{ $finalFav }}">
  <link rel="apple-touch-icon-precomposed" href="{{ $finalFav }}">

  {{-- =========================================================
       4. CSS ASSETS
       ========================================================= --}}
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cal-sans@1.0.1/index.min.css">

  <link rel="stylesheet" href="{{ asset('assets/template/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/odometer.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/swiper-bundle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/styles.css') }}">

  {{-- Fonts (template) --}}
  <link rel="stylesheet" href="{{ asset('assets/template/font/fonts.css') }}">

  {{-- Remix Icon --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('css/override.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-cursor.css') }}">

  @stack('styles')
</head>

<body>

  {{-- Dummy untuk template main.js (biar GSAP tidak warning) --}}
  <div id="preloader" aria-hidden="true"></div>
  <div class="cursor1" aria-hidden="true"></div>
  <div class="cursor2" aria-hidden="true"></div>

  {{-- Custom Cursor kamu --}}
  <div class="cursorDot" aria-hidden="true"></div>
  <div class="cursorRing" aria-hidden="true"></div>

  <div id="wrapper" class="bg-color-secondary counter-scroll">
    @isset($slot)
      {{ $slot }}
    @endisset

    @yield('content')
  </div>

  {{-- progress scroll-to-top (template) --}}
  <div class="progress-wrap" aria-hidden="true">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
  </div>

  {{-- Template JS (urutan aman) --}}
  <script src="{{ asset('assets/template/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/bootstrap.min.js') }}"></script>

  <script src="{{ asset('assets/template/js/gsap.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/ScrollTrigger.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/SplitText.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/gsapAnimate.js') }}"></script>

  <script src="{{ asset('assets/template/js/wow.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/ukiyo.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/ScrollSmooth.js') }}"></script>

  <script src="{{ asset('assets/template/js/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/infinityslide.js') }}"></script>
  <script src="{{ asset('assets/template/js/carousel.js') }}"></script>

  <script src="{{ asset('assets/template/js/main.js') }}"></script>

  <script src="{{ asset('js/custom-cursor.js') }}" defer></script>

  @stack('scripts')

  {{-- Re-declare cursor for safety if JS relies on bottom placement --}}
  <div class="cursorDot" aria-hidden="true"></div>
  <div class="cursorRing" aria-hidden="true"></div>

</body>
</html>