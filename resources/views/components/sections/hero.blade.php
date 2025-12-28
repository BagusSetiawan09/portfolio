@props([
  'firstName' => 'Bagus',
  'lastName' => 'Setiawan',

  'imageUrl' => 'images/hero/hero-img.png',
  'imageAlt' => 'Hero image',

  'availableText' => 'Available for work',

  'description' => "I've worked remotely with global teams, consulted with innovative startups, and partnered with creative impactful digital solutions for diverse business and consumer needs.",

  'hireText' => 'Hire Me',
  'hireHref' => '#contact',

  'sideTitle1' => 'Frontend',
  'sideTitle2' => 'Developer',
  'location' => 'Based in Indonesia',

  // samakan dengan ID section (work / project)
  'viewAllHref' => '#project',

  // marquee
  'itemsTop' => null,
  'itemsBottom' => null,
])

@php
  use Illuminate\Support\Str;

  // resolver path gambar: "images/..." berarti ambil dari assets/template/images/...
  $src = function ($v) {
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://', 'https://'])) return $v;

    $v = ltrim($v, '/');

    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);

    return asset($v);
  };


  $img = $src($imageUrl);

  $itemsTop = $itemsTop ?? ["© {$firstName} {$lastName}", "{$sideTitle1} {$sideTitle2}", now()->year . " portfolio"];
  $itemsBottom = $itemsBottom ?? ["{$sideTitle1} {$sideTitle2}", now()->year . " portfolio", "© {$firstName} {$lastName}"];
@endphp

<div class="page-title" id="top">
  <div class="tf-container">
    <div class="hero-banner">
      <div class="image">
        <div class="row">
          <div class="col-md-6 mx-auto tf-animate-1 active-animate">
            <img loading="lazy" decoding="async" width="695" height="854" src="{{ $img }}" alt="{{ $imageAlt }}">
          </div>
        </div>
      </div>

      <div class="content-wrap">
        <div class="content">
          <div class="hero-title text_white split-text split-lines-rotation-x">
            I’m {{ $firstName }} <br>{{ $lastName }}

            <div class="item text-button d-none d-sm-flex animateFade" data-fade-from="right" data-delay="1">
              {{ $availableText }}
            </div>
          </div>

          <p class="desc font2 split-text split-lines-transform">
            {{ $description }}
          </p>

          <a href="{{ $hireHref }}" class="tf-btn btn-bg-primary btn-hover-animation-fill animateFade">
            <span>
              <i class="ri-arrow-right-line arr-1"></i>
              <span class="btn-text h5 text_secondary-color">{{ $hireText }}</span>
              <i class="ri-arrow-right-line arr-2"></i>
            </span>
            <span class="bg-effect"></span>
          </a>
        </div>
      </div>

      <div class="content-wrap-2">
        <div class="box-title-2">
          <div class="hero-title-2 text-clip split-text split-lines-rotation-x">
            {{ $sideTitle1 }} <br>{{ $sideTitle2 }}
          </div>
          <p class="sub text-white split-text split-lines-transform">
            {{ $location }}
          </p>
        </div>
      </div>

      <div class="btn_wrapper animateFade">
        <a href="{{ $viewAllHref }}" class="btn-view-all md-hide btn-item btn-hover btn-hover-animation-fill">
          <span class="text h5 text-center">
            View all <br> PROJECT
          </span>
          <i class="icon icon-ArrowDown"></i>
          <span class="bg-effect"></span>
        </a>
      </div>
    </div>
  </div>

  <div class="wrap-partner-infinity">
    <div class="wrap-marquee bg-primary-color marquee-1">
      <div class="infiniteslide" data-clone="2" data-style="left">
        @for($r=0; $r<3; $r++)
          @foreach($itemsTop as $i => $text)
            <div class="marquee-item">
              <h1 class="{{ $i === 1 ? 'text-border-2' : 'text_secondary-color' }}">{{ $text }}</h1>
            </div>
          @endforeach
        @endfor
      </div>
    </div>

    <div class="wrap-marquee bg-white-color marquee-2">
      <div class="infiniteslide" data-clone="2" data-style="right">
        @for($r=0; $r<3; $r++)
          @foreach($itemsBottom as $i => $text)
            <div class="marquee-item">
              <h1 class="{{ $i === 0 ? 'text-border-2' : 'text_secondary-color' }}">{{ $text }}</h1>
            </div>
          @endforeach
        @endfor
      </div>
    </div>
  </div>
</div>
