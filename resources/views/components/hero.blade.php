@props([
  'role' => 'Frontend Developer',
  'location' => 'Based in Indonesia',

  'firstName' => 'Bagus',
  'lastName' => 'Setiawan',

  // support kebab-case usage from parent: :image-url="..."
  'imageUrl' => '',
  'imageAlt' => 'Hero photo',

  'badgeText' => 'Available for work',
  'description' => "I’ve worked remotely with global teams, consulted with innovative startups, and partnered with creative impactful digital solutions for diverse business and consumer needs.",
  'hireText' => 'Hire Me',
  'projectsText' => "View all\nPROJECT",
])

@php
  // normalize multiline text for circle
  $projectsTextHtml = nl2br(e($projectsText));
@endphp

<section class="hero hero--full" id="home" aria-label="Hero">
  <div class="hero2">

    {{-- LEFT --}}
    <div class="hero2__left">
      <div class="hero2__kicker reveal">
        <span class="chip">{{ $role }}</span>
        <span class="muted">{{ $location }}</span>
      </div>

      <h1 class="hero2__title reveal">
        I’m {{ $firstName }}<br>
        {{ $lastName }}
      </h1>

      <div class="hero2__badge" aria-label="{{ $badgeText }}">
        {{ $badgeText }}
      </div>

      <p class="hero2__desc reveal">
        {{ $description }}
      </p>

      <a class="hero2__hire reveal" href="#contact" aria-label="{{ $hireText }}">
        <span>{{ $hireText }}</span>
        <i class="ri-arrow-right-line" aria-hidden="true"></i>
      </a>
    </div>

    {{-- CENTER (IMAGE) --}}
    <div class="hero2__media reveal">
      <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" loading="lazy" decoding="async">
    </div>

    {{-- RIGHT --}}
    <div class="hero2__right">
      <a class="hero2__circle reveal" href="#work" aria-label="View all projects">
        <span class="hero2__circleText">{!! $projectsTextHtml !!}</span>
        <i class="ri-arrow-down-line hero2__circleArrow" aria-hidden="true"></i>
      </a>

      <div class="hero2__outline" aria-hidden="true">UI/UX</div>
    </div>

  </div>
</section>
