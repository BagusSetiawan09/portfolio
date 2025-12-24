@php
  $services = [
    [
      'title' => 'Web App Development',
      'desc'  => 'Building fast, secure, and scalable web apps for businesses ranging from dashboards, internal systems, to SaaS products.',
      'tags'  => ['Web Application', 'Dashboard', 'Landing Page', 'Performance'],
      'img'   => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1800&q=80',
    ],
    [
      'title' => 'UI/UX & Prototyping',
      'desc'  => 'Designing clear and modern user experiences from wireframes, design systems, to interactive prototyping in Figma.',
      'tags'  => ['UX Research', 'Wireframe', 'Figma', 'Design System'],
      'img'   => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1800&q=80',
    ],
    [
      'title' => 'CMS & Website Builder',
      'desc'  => 'An easy-to-manage website with an SEO-ready CMS, a clean structure, and ready for content & marketing needs.',
      'tags'  => ['WordPress', 'Headless', 'SEO', 'Maintenance'],
      'img'   => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1800&q=80',
    ],
    [
      'title' => 'Graphic Design',
      'desc'  => 'Visual design for brand and social media content promotion, posters, banners, and other graphic needs.',
      'tags'  => ['Branding', 'Social Media', 'Poster', 'Visual Identity'],
      'img'   => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1800&q=80',
    ],
  ];

  $first = $services[0];
@endphp

<section class="servicesShow" id="services" aria-label="Our Services">
  <div class="container servicesShow__wrap">

    <div class="servicesShow__top reveal">
      <a class="servicesShow__pill" href="#services" aria-label="Our Services">
        <span>OUR SERVICES</span>
        <i class="ri-arrow-right-up-line servicesShow__pillIcon" aria-hidden="true"></i>
      </a>

      <div class="servicesShow__line" aria-hidden="true"></div>
    </div>

    <div
      class="servicesShow__grid reveal"
      data-services
      data-services-total="{{ count($services) }}"
    >
      {{-- LEFT --}}
      <div class="servicesShow__left">
        <h2 class="servicesShow__title" data-services-title>{{ $first['title'] }}</h2>

        <p class="servicesShow__desc" data-services-desc>{{ $first['desc'] }}</p>

        <div class="servicesShow__tags" data-services-tags>
          @foreach($first['tags'] as $tag)
            <span class="servicesShow__tag">{{ $tag }}</span>
          @endforeach
        </div>

        <a class="servicesShow__ctaBtn" href="#contact">
          View Services
          <i class="ri-arrow-right-up-line" aria-hidden="true"></i>
        </a>

        <div class="servicesShow__nav" aria-label="Service navigation">
          <button class="servicesShow__navBtn" type="button" data-services-prev aria-label="Previous service">
            <i class="ri-arrow-left-line" aria-hidden="true"></i>
          </button>

          <div class="servicesShow__dots" data-services-dots aria-label="Choose service">
            @foreach($services as $i => $s)
              <button
                class="servicesShow__dot {{ $i === 0 ? 'is-active' : '' }}"
                type="button"
                data-services-dot="{{ $i }}"
                aria-label="Go to {{ $s['title'] }}"
                aria-pressed="{{ $i === 0 ? 'true' : 'false' }}"
              ></button>
            @endforeach
          </div>

          <button class="servicesShow__navBtn" type="button" data-services-next aria-label="Next service">
            <i class="ri-arrow-right-line" aria-hidden="true"></i>
          </button>
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="servicesShow__right">
        <div class="servicesShow__num" data-services-num>01</div>

        <div class="servicesShow__media">
          <img
            data-services-img
            src="{{ $first['img'] }}"
            alt="{{ $first['title'] }}"
            loading="lazy"
          />
        </div>
      </div>

      {{-- JSON untuk JS --}}
      <script type="application/json" data-services-json>
        {!! json_encode($services, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
      </script>
    </div>

  </div>
</section>
