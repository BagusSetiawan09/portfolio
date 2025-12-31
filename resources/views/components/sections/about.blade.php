@props([
  // Default bawaan template (Fallback)
  'years' => 3,
  'roleLine1' => 'FRONTEND DEVELOPER',
  'roleLine2' => 'BASED IN INDONESIA',
  'aboutImage' => 'images/section/about-2.jpg', // Gambar default
  'lineImage' => 'images/section/line.png',
  
  // Counters Default
  'counters' => [
    ['value' => 99,  'suffix' => '%', 'label' => "CODE\nACCURACY"],
    ['value' => 87,  'suffix' => '',  'label' => "PROJECT\nDEPLOYED"],
    ['value' => 129, 'suffix' => '+', 'label' => "HAPPY\nCLIENTS"],
  ],
])

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // 1. AMBIL DATA PROFILE DARI ADMIN
  $p = $profile ?? null;

  // 2. MAPPING DATA DINAMIS DARI PROFILE
  
  // HEADLINE (Teks Besar) -> Diambil dari 'bio_summary'
  // Jika kosong, pakai default dengan tahun pengalaman dari props
  $headlineText = $p && $p->bio_summary 
      ? strip_tags($p->bio_summary) 
      : "With over {$years}+ years of experience in Frontend Development, I build fast, accessible, and scalable web applications.";

  // ROLE -> Diambil dari 'role'
  $roleText = $p && $p->role ? strtoupper($p->role) : $roleLine1;

  // DESKRIPSI (Paragraf) -> Diambil dari 'bio_details'
  $descContent = $p && $p->bio_details 
      ? $p->bio_details 
      : "I focus on pixel-perfect UI, performance optimization, and clean architecture—turning great designs into fast, maintainable products.";

  // GAMBAR ABOUT
  $src = function ($v) {
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://', 'https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };
  
  $finalImage = $src($aboutImage); 
@endphp

<div id="about" class="section-about-2 section">
  <div class="tf-container">

    {{-- ROW 1: HEADER --}}
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
          {{-- HEADLINE BESAR --}}
          <h3 class="text-change-color reveal-type">
            {{ $headlineText }}
          </h3>
        </div>
        <div>
          {{-- ROLE --}}
          <p class="text_white text-uppercase">{{ $roleText }}</p>
          <p class="text_white text-uppercase">{{ $roleLine2 }}</p>
        </div>
      </div>
    </div>

    {{-- ROW 2: KONTEN --}}
    <div class="row justify-content-end">
      <div class="col-xxl-10">
        <div class="box-about">
          
          {{-- GAMBAR KIRI --}}
          <div class="thumbs scale-img">
            <img
              loading="lazy"
              decoding="async"
              width="325"
              height="436"
              src="{{ $finalImage }}"
              alt="about"
            >
          </div>

          {{-- KONTEN KANAN --}}
          <div class="content">
            
            {{-- DESKRIPSI --}}
            <div class="font2 desc rich-text-reset">
                {!! $descContent !!}
            </div>

            <div class="line">
              <img src="{{ $src($lineImage) }}" alt="line">
            </div>

            {{-- COUNTERS --}}
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

{{-- STYLING RICH TEXT --}}
<style>
    .rich-text-reset {
        color: #b0b0b0;
    }
    .rich-text-reset p {
        margin-bottom: 15px;
    }
    .rich-text-reset strong {
        color: #ffffff;
        font-weight: bold;
    }
    .rich-text-reset ul {
        list-style-type: disc;
        padding-left: 20px;
        margin-bottom: 15px;
    }
    .rich-text-reset li {
        margin-bottom: 5px;
    }
</style>