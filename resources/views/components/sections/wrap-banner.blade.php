@props([
  'row1' => null,
  'row2' => null,
])

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // 1. AMBIL DATA DARI FILAMENT
  $p = $profile ?? null;

  // Helper Gambar/Icon
  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  // 2. LOGIKA DATA DINAMIS
  // Jika database kosong, pakai data default (hardcode)
  
  // -- Row 1 --
  $defaultRow1 = [
    ['type' => 'img',  'value' => 'images/logo/item-1.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '129+ Satisfied customers', 'class' => 'text_white'],
    ['type' => 'img',  'value' => 'images/logo/item-2.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '87 Project completed', 'class' => 'text-border'],
  ];
  $row1Items = ($p && !empty($p->banner_row_1)) ? $p->banner_row_1 : ($row1 ?? $defaultRow1);

  // -- Row 2 --
  $defaultRow2 = [
    ['type' => 'img',  'value' => 'images/logo/item-3.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '99% customer satisfaction', 'class' => 'text_white'],
    ['type' => 'img',  'value' => 'images/logo/item-4.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '3+ years of experience', 'class' => 'text-border'],
  ];
  $row2Items = ($p && !empty($p->banner_row_2)) ? $p->banner_row_2 : ($row2 ?? $defaultRow2);

@endphp

<div class="wrap-banner" aria-label="Wrap banner">
  {{-- ROW 1 (GERAK KIRI) --}}
  <div class="wrap-infiniteslide mb_17">
    <div class="infiniteslide" data-clone="2" data-style="left">
      @foreach($row1Items as $it)
        <div class="marquee-item">
          @if(($it['type'] ?? 'text') === 'img')
            {{-- MODE GAMBAR/ICON --}}
            <div class="icon">
              <img src="{{ $src($it['value']) }}" alt="icon" style="max-height: 40px; width: auto;">
            </div>
          @else
            {{-- MODE TEKS --}}
            <div class="text {{ $it['class'] ?? 'text_white' }}">
                {{ $it['value'] }}
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  {{-- ROW 2 (GERAK KANAN) --}}
  <div class="wrap-infiniteslide">
    <div class="infiniteslide" data-clone="2" data-style="right">
      @foreach($row2Items as $it)
        <div class="marquee-item">
          @if(($it['type'] ?? 'text') === 'img')
            <div class="icon">
              <img src="{{ $src($it['value']) }}" alt="icon" style="max-height: 40px; width: auto;">
            </div>
          @else
            <div class="text {{ $it['class'] ?? 'text_white' }}">
                {{ $it['value'] }}
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</div>