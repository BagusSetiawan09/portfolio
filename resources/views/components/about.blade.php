@props([
  'years' => 3,
  'roleLine1' => 'FRONTEND DEVELOPER',
  'roleLine2' => 'DEVELOPER BASED IN INDONESIAN',
  'imageUrl' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=1400&q=80',
  'imageAlt' => 'About photo',

  'headline' => null,
  'description' => null,

  'kpis' => null,
])

@php
  $headlineText = $headline
    ?? "With over {$years} years of experience as a Frontend Developer, I specialize in building high-performance web applications that are not only visually stunning but also highly functional and scalable. I am dedicated to bridging the gap between design and technology to create seamless digital experiences that drive user engagement and business success.";

  $descText = $description
    ?? "I pride myself on my ability to transform sophisticated designs into pixel-perfect, maintainable code. By leveraging modern frameworks and clean architecture, I ensure that every application I build is optimized for speed, accessibility, and long-term scalability.";

  $items = $kpis ?? [
    ['value' => 99,  'suffix' => '%', 'label' => "CODE\nACCURACY"],
    ['value' => 87,  'suffix' => '',  'label' => "PROJECT\nDEPLOYED"],
    ['value' => 129, 'suffix' => '+', 'label' => "HAPPY\nCLIENTS"],
  ];
@endphp

<section class="aboutV2" id="about" aria-label="About">
  <div class="container aboutV2__grid">

    {{-- kiri atas: CTA --}}
    <a class="aboutV2__ctaLink reveal" href="#about" aria-label="About me">
      <span class="aboutV2__ctaText">ABOUT ME</span>
      <i class="ri-arrow-right-up-line aboutV2__ctaIcon" aria-hidden="true"></i>


</a>


    {{-- kanan atas: headline + meta --}}
    <div class="aboutV2__top reveal">
      <h2 class="aboutV2__headline">
        {{ $headlineText }}
      </h2>

      <div class="aboutV2__meta">
        <div class="aboutV2__metaText">
          {{ $roleLine1 }}<br>
          {{ $roleLine2 }}
        </div>
      </div>
    </div>

    {{-- kiri bawah: image --}}
    <div class="aboutV2__media reveal">
      <img
        src="{{ $imageUrl }}"
        alt="{{ $imageAlt }}"
        loading="lazy"
        decoding="async"
      >
    </div>

    {{-- kanan bawah: desc + rule + kpis --}}
    <div class="aboutV2__bottom reveal">
      <p class="aboutV2__desc">{{ $descText }}</p>

      <div class="aboutV2__rule" aria-hidden="true"></div>

      <div class="aboutV2__kpis" aria-label="Key metrics">
        @foreach($items as $k)
          <div class="aboutV2__kpi">
            <div class="aboutV2__num">
              <span data-counter="{{ $k['value'] }}">0</span>{{ $k['suffix'] }}
            </div>

            <div class="aboutV2__label">
              {!! nl2br(e($k['label'])) !!}
            </div>
          </div>
        @endforeach
      </div>
    </div>

  </div>
</section>
