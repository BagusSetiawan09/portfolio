@props([
  'id' => 'work',
  'title' => null,
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

  $default = [
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
      'title' => 'Gunung Aroma',
      'img'   => 'images/portfolio/portfolio-11.jpg',
      'href'  => $defaultHref,
      'tags'  => ['Design', 'Social Media'],
    ],
  ];

  $data = $items;

  // normalize collection -> array
  if ($data instanceof \Illuminate\Support\Collection) {
    $data = $data->all();
  }

  // fallback kalau kosong
  if (empty($data)) {
    $data = $default;
  }

  // normalize item DB (Project model) -> format component
  $data = collect($data)->map(function ($it) use ($defaultHref) {
    if ($it instanceof \App\Models\Project) {
      $it = $it->toArray();
    }

    return [
      'title' => $it['title'] ?? 'Portfolio',
      'img'   => $it['img'] ?? $it['image_url'] ?? null,
      'href'  => $it['href'] ?? $it['link_url'] ?? $defaultHref,
      'tags'  => $it['tags'] ?? [],
    ];
  })->all();
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

          // support field img / image_url
          $imgRaw = $it['img'] ?? ($it['image_url'] ?? '');
          $img  = $src($imgRaw);

          // support tags array / string
          $tags = $it['tags'] ?? [];
          if (is_string($tags)) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tags))));
          }
          if (!is_array($tags)) $tags = [];

          $name = $it['title'] ?? 'Portfolio';
        @endphp

        <div class="portfolio-item style-2">
          <a href="{{ $href }}" class="img-style scale-img" aria-label="{{ $name }}" @if(Str::startsWith($href, ['http://','https://'])) target="_blank" rel="noopener" @endif>
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
            <a href="{{ $href }}" class="mb_16 title link" @if(Str::startsWith($href, ['http://','https://'])) target="_blank" rel="noopener" @endif>
              {{ $name }}
            </a>

            @if(!empty($tags))
              <ul class="category">
                @foreach($tags as $t)
                  <li>
                    <a href="{{ $href }}" class="text-caption-1 text_white" @if(Str::startsWith($href, ['http://','https://'])) target="_blank" rel="noopener" @endif>
                      {{ $t }}
                    </a>
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
