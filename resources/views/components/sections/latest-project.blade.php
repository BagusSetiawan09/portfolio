@props([
  'title' => 'latest project',
  'id' => 'project',

  // bisa Collection<Project> atau array
  'projects' => null,
])

@php
  use Illuminate\Support\Str;

  $imgSrc = function ($v) {
    if (blank($v)) return '';
    if (Str::startsWith($v, ['http://', 'https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  // fallback default (template)
  $defaultItems = [
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

  $items = $projects;

  // kalau Collection -> pakai langsung, kalau kosong -> fallback
  if ($items instanceof \Illuminate\Support\Collection) {
    $items = $items->values();
  }

  $hasData =
    ($items instanceof \Illuminate\Support\Collection && $items->isNotEmpty())
    || (is_array($items) && !empty($items));

  if (! $hasData) {
    $items = $defaultItems;
  }
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
          // Normalisasi: support Model (DB) dan Array (fallback lama)
          $isModel = is_object($p);

          $titleText = $isModel ? ($p->title ?? 'Project') : ($p['title'] ?? 'Project');

          // DB pakai image_url, template lama pakai img
          $imgVal = $isModel ? ($p->image_url ?? '') : ($p['img'] ?? '');
          $img = $imgSrc($imgVal);

          // DB pakai link_url, template lama pakai href
          $href = $isModel ? ($p->link_url ?: '#') : ($p['href'] ?? '#');

          // DB tags bisa array (cast) atau json string
          $rawTags = $isModel ? ($p->tags ?? []) : ($p['tags'] ?? []);
          if (is_string($rawTags)) {
            $rawTags = json_decode($rawTags, true) ?: [];
          }
          $tags = is_array($rawTags) ? $rawTags : [];

          $year = $isModel ? ($p->year ?? null) : ($p['year'] ?? null);
        @endphp

        <div class="project-item">
          @if($i % 2 === 1)
            <div class="content">
              <a href="{{ $href }}" class="h3 text_white mb_7 link">{{ $titleText }}</a>

              <ul class="list">
                @foreach($tags as $t)
                  @if(filled($t))
                    <li class="text-caption-1 text-uppercase">{{ $t }}</li>
                  @endif
                @endforeach

                @if(filled($year))
                  <li class="text-caption-1">{{ $year }}</li>
                @endif
              </ul>
            </div>

            <div class="img-style scale-img">
              @if($img)
                <img
                  src="{{ $img }}"
                  width="896"
                  height="820"
                  alt="{{ $titleText }}"
                  loading="lazy"
                  decoding="async"
                >
              @endif
            </div>
          @else
            <div class="img-style scale-img">
              @if($img)
                <img
                  src="{{ $img }}"
                  width="896"
                  height="820"
                  alt="{{ $titleText }}"
                  loading="lazy"
                  decoding="async"
                >
              @endif
            </div>

            <div class="content">
              <a href="{{ $href }}" class="h3 text_white mb_7 link">{{ $titleText }}</a>

              <ul class="list">
                @foreach($tags as $t)
                  @if(filled($t))
                    <li class="text-caption-1 text-uppercase">{{ $t }}</li>
                  @endif
                @endforeach

                @if(filled($year))
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
