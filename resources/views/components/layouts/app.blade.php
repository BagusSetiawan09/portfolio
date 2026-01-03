<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
  @php
      use App\Models\Site;

      $siteSettings = Site::first();

      $defaultTitle = 'Portfolio — Home 02 Style';
      $defaultFav   = asset('assets/template/images/favicon.svg');

      $dbSiteName = $siteSettings->site_name ?? $defaultTitle;
      $dbDesc     = $siteSettings->site_description ?? 'Professional Portfolio Website';
      $dbKeys     = $siteSettings->keywords ?? 'portfolio, design, development';

      $finalFav   = !empty($siteSettings->favicon_url) ? $siteSettings->favicon_url : $defaultFav;

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

  <title>{{ $title ?? $dbSiteName }}</title>

  <meta name="description" content="{{ $dbDesc }}">
  <meta name="keywords" content="{{ $dbKeys }}">

  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $ogTitle }}">
  <meta property="og:description" content="{{ $ogDesc }}">
  @if(!empty($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
  @endif

  <link rel="shortcut icon" href="{{ $finalFav }}">
  <link rel="apple-touch-icon-precomposed" href="{{ $finalFav }}">

  {{-- Perf: preconnect untuk CDN/font --}}
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cal-sans@1.0.1/index.min.css">

  <link rel="stylesheet" href="{{ asset('assets/template/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/odometer.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/swiper-bundle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/styles.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/template/font/fonts.css') }}">

  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/override.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-cursor.css') }}">

  @livewireStyles

  @stack('styles')
</head>

<body>
  <div id="preloader" aria-hidden="true"></div>
  <div class="cursor1" aria-hidden="true"></div>
  <div class="cursor2" aria-hidden="true"></div>

  <div class="cursorDot" aria-hidden="true"></div>
  <div class="cursorRing" aria-hidden="true"></div>

  <div id="wrapper" class="bg-color-secondary counter-scroll">
    @isset($slot)
      {{ $slot }}
    @endisset

    @yield('content')
  </div>

  <div class="progress-wrap" aria-hidden="true">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
  </div>

  <script src="{{ asset('assets/template/js/jquery.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/bootstrap.min.js') }}" defer></script>

  <script src="{{ asset('assets/template/js/gsap.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/ScrollTrigger.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/SplitText.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/gsapAnimate.js') }}" defer></script>

  <script src="{{ asset('assets/template/js/wow.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/ukiyo.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/ScrollSmooth.js') }}" defer></script>

  <script src="{{ asset('assets/template/js/swiper-bundle.min.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/infinityslide.js') }}" defer></script>
  <script src="{{ asset('assets/template/js/carousel.js') }}" defer></script>

  <script src="{{ asset('assets/template/js/main.js') }}" defer></script>

  <script src="{{ asset('js/custom-cursor.js') }}" defer></script>

  @stack('scripts')

  {{-- ✅ LIVEWIRE SCRIPTS (WAJIB, CUMA 1x) --}}
  @livewireScripts
</body>
</html>
