<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ $title ?? 'Portfolio — Home 02 Style' }}</title>

  {{-- Cal Sans --}}
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cal-sans@1.0.1/index.min.css">

  {{-- Icon --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">


  {{-- Main CSS --}}
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

  @stack('styles')
</head>

<body>
  {{ $slot }}

  {{-- Main JS --}}
  <script src="{{ asset('js/script.js') }}" defer></script>

  @stack('scripts')

  <!-- Custom Cursor -->
<div class="cursorDot" aria-hidden="true"></div>
<div class="cursorRing" aria-hidden="true"></div>

</body>
</html>
