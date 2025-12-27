@props([
  // baris marquee 1 (kiri) & 2 (kanan)
  'row1' => null,
  'row2' => null,

  // partner logos (marquee bawah)
  // bisa URL atau 'images/logo/partner-1.png'
  'partners' => null,
])

@php
  use Illuminate\Support\Str;

  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  // default mengikuti style template (icon image + text bergantian)
  $row1Items = $row1 ?? [
    ['type' => 'img',  'value' => 'images/logo/item-1.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '129+ Satisfied customers', 'class' => 'text_white'],
    ['type' => 'img',  'value' => 'images/logo/item-2.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '87 Project completed', 'class' => 'text-border'],
  ];

  $row2Items = $row2 ?? [
    ['type' => 'img',  'value' => 'images/logo/item-3.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '99% customer satisfaction', 'class' => 'text_white'],
    ['type' => 'img',  'value' => 'images/logo/item-4.png', 'alt' => 'item'],
    ['type' => 'text', 'value' => '3+ years of experience', 'class' => 'text-border'],
  ];

@endphp

<div class="wrap-banner" aria-label="Wrap banner">
  <div class="wrap-infiniteslide mb_17">
    <div class="infiniteslide" data-clone="2" data-style="left">
      @foreach($row1Items as $it)
        <div class="marquee-item">
          @if(($it['type'] ?? 'text') === 'img')
            <div class="icon">
              <img src="{{ $src($it['value']) }}" alt="{{ $it['alt'] ?? 'icon' }}">
            </div>
          @else
            <div class="text {{ $it['class'] ?? 'text_white' }}">{{ $it['value'] }}</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  <div class="wrap-infiniteslide">
    <div class="infiniteslide" data-clone="2" data-style="right">
      @foreach($row2Items as $it)
        <div class="marquee-item">
          @if(($it['type'] ?? 'text') === 'img')
            <div class="icon">
              <img src="{{ $src($it['value']) }}" alt="{{ $it['alt'] ?? 'icon' }}">
            </div>
          @else
            <div class="text {{ $it['class'] ?? 'text_white' }}">{{ $it['value'] }}</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</div>
