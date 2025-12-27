@props([

  'bannerBg' => 'assets/template/images/section/banner-1.jpg',
])

@php
  use Illuminate\Support\Str;

  $src = function ($v) {
    $v = $v ?? '';
    if ($v === '') return '';

    if (Str::startsWith($v, ['http://', 'https://'])) return $v;

    // kalau kamu passing "images/..." artinya ambil dari assets/template/images/...
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);

    return asset($v);
  };

  $bannerSrc = $src($bannerBg);
@endphp

<div class="banner-image tf-spacing-4 container-3" aria-hidden="true">
  <div class="parallaxie parallax-img" style="background-image: url('{{ $bannerSrc }}')"></div>
</div>
