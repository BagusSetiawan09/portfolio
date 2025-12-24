@props([
  'name' => 'Bagus Setiawan',
  'role' => 'Frontend Developer',
  'year' => null,
])

@php
  $y = $year ?? now()->year;

  // ribbon atas (lime)
  $topItems = [
    ['text' => $name,           'class' => ''],
    ['text' => $role,           'class' => 'ribbon__item--stroke'],
    ['text' => "{$y} portfolio",'class' => ''],
    ['text' => "© {$name}",     'class' => ''],
  ];

  // ribbon bawah (light)
  $bottomItems = [
    ['text' => $role,           'class' => 'ribbon__item--strokeSoft'],
    ['text' => "{$y} portfolio",'class' => ''],
    ['text' => "© {$name}",     'class' => ''],
  ];

  $renderItems = function(array $items) {
    foreach ($items as $it) {
      $cls = trim("ribbon__item {$it['class']}");
      echo '<span class="'.$cls.'">'.e($it['text']).'</span>';
    }
  };
@endphp

<section class="marqueeRibbons" aria-label="Marquee ribbons">
  {{-- RIBBON HIJAU (atas) --}}
  <div class="ribbon ribbon--lime">
    <div class="ribbon__track ribbon__track--a">
      <div class="ribbon__content">
        @php $renderItems($topItems); $renderItems($topItems); @endphp
      </div>

      {{-- duplikat untuk loop mulus --}}
      <div class="ribbon__content" aria-hidden="true">
        @php $renderItems($topItems); $renderItems($topItems); @endphp
      </div>
    </div>
  </div>

  {{-- RIBBON PUTIH (bawah) --}}
  <div class="ribbon ribbon--light">
    <div class="ribbon__track ribbon__track--b">
      <div class="ribbon__content">
        @php $renderItems($bottomItems); $renderItems($bottomItems); @endphp
      </div>

      {{-- duplikat untuk loop mulus --}}
      <div class="ribbon__content" aria-hidden="true">
        @php $renderItems($bottomItems); $renderItems($bottomItems); @endphp
      </div>
    </div>
  </div>
</section>
