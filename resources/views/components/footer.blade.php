@props([
  'name' => 'Bagus Setiawan',
  'role' => 'Frontend Developer',
  'email' => 'kamu@email.com',
  'phone' => '+62 812-3456-789',
  'year' => now()->year,

  'quote' => '“Let\'s build a high-converting website that doesn\'t just look great, but actually grows your business and delivers measurable results.”',

  'links' => [
    ['label' => 'About us', 'href' => '#about'],
    ['label' => 'Experience & Education', 'href' => '#experience'],
    ['label' => 'Our Services', 'href' => '#services'],
    ['label' => 'Latest Work', 'href' => '#work'],
    ['label' => 'Client Reviews', 'href' => '#reviews'],
  ],

  'social' => [
    ['label' => 'Github', 'href' => '#', 'icon' => 'ri-github-line'],
    ['label' => 'Dribbble', 'href' => '#', 'icon' => 'ri-dribbble-line'],
    ['label' => 'Behance', 'href' => '#', 'icon' => 'ri-behance-line'],
    ['label' => 'Linkedin', 'href' => '#', 'icon' => 'ri-linkedin-line'],
    ['label' => 'Instagram', 'href' => '#', 'icon' => 'ri-instagram-line'],
  ],

  'badge' => 'Available for work',
  'metaLeft' => null,
  'metaCenter' => null,
  'metaRight' => 'Created by Bagus Setiawan',
])

@php
  $metaLeftText = $metaLeft ?? "© {$year} {$name}. All rights reserved.";
  $metaCenterText = $metaCenter ?? "{$year} Portfolio - {$role}";

  // phone untuk href tel:
  $tel = preg_replace('/[^0-9+]/', '', $phone);
@endphp

<footer class="footerX" id="contact" aria-label="Footer">
  {{-- TOP GRID (4 kolom) --}}
  <div class="footerX__top">

    {{-- col 1: quote + profile --}}
    <div class="footerX__col">
      <p class="footerX__quote">{{ $quote }}</p>

      <div class="footerX__person">
        <span class="footerX__avatar" aria-hidden="true"></span>
        <div class="footerX__personMeta">
          <div class="footerX__name">{{ $name }}</div>
          <div class="footerX__role">{{ $role }}</div>
        </div>
      </div>
    </div>

    {{-- col 2: contact --}}
    <div class="footerX__col">
      <address class="footerX__address">
        <div class="footerX__label">Please send me an email to</div>
        <a class="footerX__linkBig" href="mailto:{{ $email }}">{{ $email }}</a>

        <div class="footerX__label footerX__label--mt">Let’s talk!</div>
        <a class="footerX__linkBig" href="tel:{{ $tel }}">{{ $phone }}</a>
      </address>
    </div>

    {{-- col 3: quick link --}}
    <div class="footerX__col">
      <div class="footerX__label">Quick link</div>
      <nav class="footerX__links" aria-label="Footer quick links">
        @foreach($links as $l)
          <a href="{{ $l['href'] }}">{{ $l['label'] }}</a>
        @endforeach
      </nav>
    </div>

    {{-- col 4: social --}}
    <div class="footerX__col">
      <div class="footerX__label">Social</div>

      <div class="footerX__social" aria-label="Social links">
        @foreach($social as $s)
          <a class="footerX__socialBtn" href="{{ $s['href'] }}" aria-label="{{ $s['label'] }}">
            <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
          </a>
        @endforeach
      </div>
    </div>

  </div>

  {{-- BIG BRAND --}}
  <div class="footerBig">
    <div class="footerBig__inner">
      <div class="footerBig__titleWrap">
        <h2 class="footerBig__title">© {{ $name }}</h2>
        <span class="footerBig__badge">{{ $badge }}</span>
      </div>

      <div class="footerBig__metaRow" aria-label="Footer meta">
        <div class="footerBig__meta">{{ $metaLeftText }}</div>
        <span class="footerBig__line" aria-hidden="true"></span>
        <div class="footerBig__meta footerBig__meta--center">{{ $metaCenterText }}</div>
        <span class="footerBig__line" aria-hidden="true"></span>
        <div class="footerBig__meta footerBig__meta--right">{{ $metaRight }}</div>
      </div>
    </div>
  </div>
</footer>
