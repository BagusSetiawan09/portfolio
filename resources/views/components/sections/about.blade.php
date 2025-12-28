@props([
  'years' => 3,

  // teks kanan (headline besar)
  'headline' => null,

  // 2 baris kecil (role)
  'roleLine1' => 'FRONTEND DEVELOPER',
  'roleLine2' => 'DEVELOPER BASED IN INDONESIA',

  // gambar + deskripsi bawah
  'aboutImage' => 'images/section/about-2.jpg',
  'desc' => "I focus on pixel-perfect UI, performance optimization, and clean architecture—turning great designs into fast, maintainable products.",

  // garis dekorasi
  'lineImage' => 'images/section/line.png',

  // counter bawah
  'counters' => [
    ['value' => 99,  'suffix' => '%', 'label' => "CODE\nACCURACY"],
    ['value' => 87,  'suffix' => '',  'label' => "PROJECT\nDEPLOYED"],
    ['value' => 129, 'suffix' => '+', 'label' => "HAPPY\nCLIENTS"],
  ],
])

@php
  use Illuminate\Support\Str;

  // resolver path gambar:
  // - kalau "images/..." => ambil dari public/assets/template/images/...
  // - kalau sudah http(s) => biarkan
  // - selain itu => asset() normal
  $src = function ($v) {
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://', 'https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  $headlineText = $headline ?: "With over {$years}+ years of experience in Frontend Development, I build fast, accessible, and scalable web applications that turn complex ideas into delightful user experiences.";
@endphp

<div id="about" class="section-about-2 section">
  <div class="tf-container">

    {{-- ROW 1 (judul kiri + headline kanan) --}}
    <div class="row">
      <div class="col-lg-4">
        <div class="heading-title-2">
          <div class="d-flex align-items-center gap_7">
            <div class="text-title text_white text-uppercase">about me</div>
            <i class="ri-arrow-right-up-line" aria-hidden="true"></i>
          </div>
          <div class="cycle-line"></div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="scroll-effect mb_35">
          <h3 class="text-change-color reveal-type">{{ $headlineText }}</h3>
        </div>
        <div>
          <p class="text_white text-uppercase">{{ $roleLine1 }}</p>
          <p class="text_white text-uppercase">{{ $roleLine2 }}</p>
        </div>
      </div>
    </div>

    {{-- ROW 2 (gambar + desc + counter) --}}
    <div class="row justify-content-end">
      <div class="col-xxl-10">
        <div class="box-about">
          <div class="thumbs scale-img">
            <img
              loading="lazy"
              decoding="async"
              width="325"
              height="436"
              src="{{ $src($aboutImage) }}"
              alt="about"
            >
          </div>

          <div class="content">
            <p class="font2 desc">{{ $desc }}</p>

            <div class="line">
              <img src="{{ $src($lineImage) }}" alt="line">
            </div>

            <div class="wrap-couter d-flex">
              @foreach($counters as $i => $c)
                <div class="counter-item style-3 counter-{{ $i + 1 }}">
                  <div class="counter text-display-2">
                    <span
                      class="numberCount"
                      data-count="{{ $c['value'] }}"
                      data-duration="500"
                    >0</span>
                    @if(!empty($c['suffix']))
                      <span class="sub-counter">{{ $c['suffix'] }}</span>
                    @endif
                  </div>

                  <p class="sub-title text_white text-uppercase">
                    {!! nl2br(e($c['label'])) !!}
                  </p>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
