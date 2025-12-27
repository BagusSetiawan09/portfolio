@props([
  'name' => 'Bagus Setiawan',
  'role' => 'Frontend Developer',
  'year' => null,

  // kalau mau custom item, bisa kirim dari parent
  'itemsTop' => null,
  'itemsBottom' => null,
])

@php
  $y = $year ?? now()->year;

  $top = $itemsTop ?? [
    "© {$name}",
    $role,
    "{$y} portfolio",
  ];

  $bottom = $itemsBottom ?? [
    $role,
    "{$y} portfolio",
    "© {$name}",
  ];
@endphp

<div class="wrap-partner-infinity" aria-label="Marquee">
  {{-- ribbon atas (bg primary) --}}
  <div class="wrap-marquee bg-primary-color marquee-1">
    <div class="infiniteslide" data-clone="2" data-style="left">
      @foreach($top as $t)
        <div class="marquee-item">
          <h1 class="text_secondary-color">{{ $t }}</h1>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ribbon bawah (bg putih) --}}
  <div class="wrap-marquee bg-white-color marquee-2">
    <div class="infiniteslide" data-clone="2" data-style="right">
      @foreach($bottom as $b)
        <div class="marquee-item">
          <h1 class="text_secondary-color">{{ $b }}</h1>
        </div>
      @endforeach
    </div>
  </div>
</div>
