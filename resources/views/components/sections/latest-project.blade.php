@props([
  'title' => 'latest project',
  'id' => 'project',

  // format item:
  // [
  //   'title' => 'Project One',
  //   'tags'  => ['Laravel','API'],
  //   'year'  => '2025',
  //   'img'   => 'https://...' atau 'images/section/project-1.jpg',
  //   'href'  => '#', // optional
  // ]
  'projects' => null,
])

@php
  use Illuminate\Support\Str;

  $imgSrc = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  $items = $projects ?? [
    [
      'title' => 'Project One',
      'tags'  => ['Laravel', 'Web App'],
      'year'  => now()->year,
      'img'   => 'images/section/project-1.jpg',
      'href'  => '#',
    ],
    [
      'title' => 'Project Two',
      'tags'  => ['Frontend', 'UI'],
      'year'  => now()->year,
      'img'   => 'images/section/project-2.jpg',
      'href'  => '#',
    ],
  ];

@endphp

<section id="{{ $id }}" class="section-project tf-spacing-4" aria-label="Latest Project">
  <div class="tf-container">
    <div class="heading-title-2 mb_80">
      <div class="d-flex align-items-center gap_7">
        <div class="text-title text_white text-uppercase">{{ $title }}</div>
        <i class="ri-arrow-right-up-line" aria-hidden="true"></i>
      </div>
      <div class="cycle-line" aria-hidden="true"></div>
    </div>
  </div>

  <div class="container-3">
    <div class="tf-grid-layout md-col-2 gap_16">
      @foreach($items as $i => $p)
        @php
          $href = $p['href'] ?? '#';
          $img  = $imgSrc($p['img'] ?? '');
          $tags = $p['tags'] ?? [];
          $year = $p['year'] ?? null;
          $titleText = $p['title'] ?? 'Project';
        @endphp

        <div class="project-item">
          @if($i % 2 === 1)
            <div class="content">
              <a href="{{ $href }}" class="h3 text_white mb_7 link">{{ $titleText }}</a>
              <ul class="list">
                @foreach($tags as $t)
                  <li class="text-caption-1 text-uppercase">{{ $t }}</li>
                @endforeach
                @if(!empty($year))
                  <li class="text-caption-1">{{ $year }}</li>
                @endif
              </ul>
            </div>

            <div class="img-style scale-img">
              @if($img)
                <img src="{{ $img }}" width="896" height="820" alt="{{ $titleText }}" loading="lazy" decoding="async">
              @endif
            </div>
          @else
            <div class="img-style scale-img">
              @if($img)
                <img src="{{ $img }}" width="896" height="820" alt="{{ $titleText }}" loading="lazy" decoding="async">
              @endif
            </div>

            <div class="content">
              <a href="{{ $href }}" class="h3 text_white mb_7 link">{{ $titleText }}</a>
              <ul class="list">
                @foreach($tags as $t)
                  <li class="text-caption-1 text-uppercase">{{ $t }}</li>
                @endforeach
                @if(!empty($year))
                  <li class="text-caption-1">{{ $year }}</li>
                @endif
              </ul>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
