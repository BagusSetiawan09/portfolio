@props([
  'title' => 'LATEST PROJECT',
  'pillHref' => '#work',
  'projects' => null,
])

@php
  $items = $projects ?? [
    [
      'title' => 'Admin Dashboard',
      'cat' => 'webapp',
      'tags' => ['Dashboard','Laravel','API'],
      'img' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1600&q=80',
    ],
    [
      'title' => 'Mobile App UI Kit',
      'cat' => 'uiux',
      'tags' => ['Figma','UI Kit','Prototype'],
      'img' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1600&q=80',
    ],
    [
      'title' => 'Company Profile Website',
      'cat' => 'cms',
      'tags' => ['WordPress','SEO','CMS'],
      'img' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=1600&q=80',
    ],
    [
      'title' => 'Brand Identity Pack',
      'cat' => 'graphic',
      'tags' => ['Branding','Logo','Social'],
      'img' => 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?auto=format&fit=crop&w=1600&q=80',
    ],
    [
      'title' => 'SaaS Landing Page',
      'cat' => 'webapp',
      'tags' => ['Landing','Performance','UI'],
      'img' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=1600&q=80',
    ],
    [
      'title' => 'E-commerce Wireframe',
      'cat' => 'uiux',
      'tags' => ['Wireframe','UX','Flow'],
      'img' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1600&q=80',
    ],
  ];
@endphp

<section class="latest" id="work" aria-label="Latest Projects">
  <div class="container latest__wrap">

    {{-- pill title --}}
    <div class="latest__top reveal">
      <a class="latest__pill" href="{{ $pillHref }}" aria-label="{{ $title }}">
        <span>{{ $title }}</span>
        <i class="ri-arrow-right-up-line latest__pillArrow" aria-hidden="true"></i>
      </a>
    </div>

    {{-- grid --}}
    <div class="latestGrid reveal" data-project-grid>
      @foreach($items as $p)
        <article class="latestItem" data-project data-type="{{ $p['cat'] }}">
          <div class="latestItem__media">
            <img
              src="{{ $p['img'] }}"
              alt="{{ $p['title'] }}"
              loading="lazy"
              decoding="async"
            >
          </div>

          <div class="latestItem__body">
            <h3 class="latestItem__title">{{ $p['title'] }}</h3>

            @if(!empty($p['tags']))
              <div class="latestItem__tags" aria-label="Project tags">
                @foreach($p['tags'] as $t)
                  <span class="latestItem__tag">{{ $t }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </article>
      @endforeach
    </div>

  </div>
</section>
