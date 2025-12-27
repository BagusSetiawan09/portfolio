<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <meta name="theme-color" content="#000000">
  <meta name="msapplication-navbutton-color" content="#000000">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


  <title>{{ $title ?? 'Portfolio — Home 02 Style' }}</title>

  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cal-sans@1.0.1/index.min.css">

  {{-- TEMPLATE CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/template/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/odometer.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/swiper-bundle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/template/css/styles.css') }}">

  {{-- Fonts (template) --}}
  <link rel="stylesheet" href="{{ asset('assets/template/font/fonts.css') }}">

  {{-- Remix Icon --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  {{-- <link rel="stylesheet" href="{{ asset('assets/template/icons/icomoon/style.css') }}"> --}}

  <link rel="shortcut icon" href="{{ asset('assets/template/images/favicon.svg') }}">
  <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/template/images/favicon.svg') }}">

  {{-- CSS kamu (override di paling bawah supaya tampilan tidak berubah) --}}
  {{-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('css/override.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-cursor.css') }}">

  @stack('styles')
</head>

<body>

  {{-- Dummy untuk template main.js (biar GSAP tidak warning) --}}
  <div id="preloader" aria-hidden="true"></div>
  <div class="cursor1" aria-hidden="true"></div>
  <div class="cursor2" aria-hidden="true"></div>

  {{-- Cursor bawaan template (dibutuhkan oleh template main.js) --}}
  {{-- <div class="cursor1" aria-hidden="true"></div>
  <div class="cursor2" aria-hidden="true"></div> --}}

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

  {{-- <script src="{{ asset('assets/template/js/odometer.min.js') }}"></script> --}}
  <script src="{{ asset('assets/template/js/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/template/js/infinityslide.js') }}"></script>
  <script src="{{ asset('assets/template/js/carousel.js') }}"></script>

  <script src="{{ asset('assets/template/js/main.js') }}"></script>

  <script src="{{ asset('js/custom-cursor.js') }}" defer></script>

  {{-- JS kamu (taruh setelah main.js biar tidak tabrakan init plugin) --}}
  {{-- <script src="{{ asset('js/script.js') }}" defer></script> --}}

  @stack('scripts')

  <div class="cursorDot" aria-hidden="true"></div>
  <div class="cursorRing" aria-hidden="true"></div>

</body>
</html>
