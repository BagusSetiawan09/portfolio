@props([
  'title' => 'Our Services',
  'viewText' => 'View SERVICES',
  'viewHref' => '#contact',

  // format item:
  // [
  //   'title' => 'Front-End Development',
  //   'tags'  => ['Web Application','Mobile apps','Database','Plug-in'],
  //   'img'   => 'images/section/service-1.jpg'  // bisa URL atau path lokal
  //   'href'  => '#', // optional
  // ]
  'services' => null,
])

@php
  use Illuminate\Support\Str;

  // helper untuk gambar: support URL, assets lokal, atau path bawaan template "images/..."
  $imgSrc = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;

    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);

    return asset($v);
  };

  $items = $services ?? [
    [
      'title' => 'Front-End Development',
      'tags'  => ['Web Application', 'Mobile apps', 'Database', 'Plug-in'],
      'img'   => 'images/section/service-1.jpg',
      'href'  => $viewHref,
    ],
    [
      'title' => 'Web App Development',
      'tags'  => ['Web Application', 'Mobile apps', 'Database', 'Plug-in'],
      'img'   => 'images/section/service-2.jpg',
      'href'  => $viewHref,
    ],
    [
      'title' => 'CMS & Website Builders',
      'tags'  => ['Web Application', 'Mobile apps', 'Database', 'Plug-in'],
      'img'   => 'images/section/service-3.jpg',
      'href'  => $viewHref,
    ],
    [
      'title' => 'Graphic Design',
      'tags'  => ['Branding', 'Social Media', 'Poster', 'Visual Identity'],
      'img'   => 'images/section/service-3.jpg',
      'href'  => $viewHref,
    ],
  ];
@endphp

<div id="service" class="section-services section" aria-label="Our Services">
  <div class="tf-container">
    <div class="heading-title-2 has-border mb_80">
      <div class="d-flex align-items-center gap_10 text-uppercase">
        <div class="text-title text_white">{{ $title }}</div>
        <i class="ri-arrow-right-up-line icon-ArrowUpRight" aria-hidden="true"></i>
      </div>
      <div class="cycle-line" aria-hidden="true"></div>
    </div>

    <div class="wrap-services">
      @foreach($items as $i => $s)
        @php
          $num = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
          $href = $s['href'] ?? $viewHref;
          $titleText = $s['title'] ?? 'Service';
          $tags = $s['tags'] ?? [];
          $img = $imgSrc($s['img'] ?? '');
        @endphp

        <div class="service-item wow animate__animated animate__fadeInUp"
             data-wow-duration="2s"
             data-wow-delay="{{ number_format($i * 0.1, 1) }}s">

          <div class="box-left">
            <div class="heading">
              <a href="{{ $href }}" class="h1 link text_white">{{ $titleText }}</a>

              @if(!empty($tags))
                <ul class="category">
                  @foreach($tags as $t)
                    <li><a href="{{ $href }}" class="text-caption-1 text_white">{{ $t }}</a></li>
                  @endforeach
                </ul>
              @endif
            </div>

            <div class="btn_wrapper">
              <a href="{{ $href }}" class="tf-btn-2 btn-item btn-hover" aria-label="{{ $viewText }}">
                <span class="h5">{{ $viewText }}</span>
                <i class="ri-arrow-right-up-line icon-ArrowUpRight" aria-hidden="true"></i>
              </a>
            </div>
          </div>

          <div class="box-right">
            <div class="number">{{ $num }}</div>

            <div class="img-style scale-img">
              @if($img)
                <img
                  src="{{ $img }}"
                  width="448"
                  height="490"
                  alt="service"
                  loading="lazy"
                  decoding="async"
                >
              @endif
            </div>
          </div>

        </div>
      @endforeach
    </div>

  </div>
</div>
