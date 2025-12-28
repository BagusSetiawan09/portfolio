@props([
  'id' => 'work',
  'title' => null, // optional kalau mau tambah heading
  'items' => null,

  // default href kalau item tidak punya href
  'defaultHref' => '#',
])

@php
  use Illuminate\Support\Str;

  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  // format item:
  // [
  //   'title' => 'Portfolio Item',
  //   'img' => 'https://...' atau 'images/portfolio/portfolio-9.jpg',
  //   'href' => '#',
  //   'tags' => ['Web Application','UI'],
  // ]
  $data = $items ?? [
    [
      'title' => 'Website SMKS PAB 2 Helvetia',
      'img'   => 'images/portfolio/portfolio-9.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Landing Page', 'Wordpress'],
    ],
    [
      'title' => 'Java Infra Gas',
      'img'   => 'images/portfolio/portfolio-10.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Landing Page', 'Wordpress'],
    ],
    [
      'title' => 'Bali Inter Gas',
      'img'   => 'images/portfolio/portfolio-11.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Landing Page', 'Wordpress'],
    ],
    [
      'title' => 'Jakarta Indo Service',
      'img'   => 'images/portfolio/portfolio-11.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Landing Page', 'Wordpress'],
    ],
    [
      'title' => 'Gunung Aroma',
      'img'   => 'images/portfolio/portfolio-11.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Design', 'Social Media'],
    ],
  ];
@endphp

<section id="{{ $id }}" class="section-portfoli-1 tf-spacing-5 section" aria-label="Portfolio">
  <div class="tf-container">
    @if(!empty($title))
      <div class="heading-title-2 mb_80">
        <div class="d-flex align-items-center gap_7">
          <div class="text-title text_white text-uppercase">{{ $title }}</div>
          <i class="ri-arrow-right-up-line" aria-hidden="true"></i>
        </div>
        <div class="cycle-line" aria-hidden="true"></div>
      </div>
    @endif

    <div class="wrap-portfolio">
      @foreach($data as $it)
        @php
          $href = $it['href'] ?? $defaultHref;
          $img  = $src($it['img'] ?? '');
          $tags = $it['tags'] ?? [];
          $name = $it['title'] ?? 'Portfolio';
        @endphp

        <div class="portfolio-item style-2">
          <a href="{{ $href }}" class="img-style scale-img" aria-label="{{ $name }}">
            @if($img)
              <img
                src="{{ $img }}"
                width="696"
                height="688"
                alt="{{ $name }}"
                loading="lazy"
                decoding="async"
              >
            @endif
          </a>

          <div class="content">
            <a href="{{ $href }}" class="mb_16 title link">{{ $name }}</a>

            @if(!empty($tags))
              <ul class="category">
                @foreach($tags as $t)
                  <li>
                    <a href="{{ $href }}" class="text-caption-1 text_white">{{ $t }}</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
