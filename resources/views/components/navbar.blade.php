@props([
  'brand' => 'Bagus Setiawan',
  'email' => 'bagussetiawan.lz24@gmail.com',
  'phone' => '0895-6288-94070',
  'availableText' => 'Available for work',

  'links' => [
    ['label' => 'About us', 'href' => '#about'],
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
])

@php
  $tel = preg_replace('/[^0-9+]/', '', $phone);
@endphp

<header class="header" id="top" aria-label="Header">
  <div class="container header__inner">
    <a class="brand" href="#top" aria-label="Home">
      <span class="brand__mark" aria-hidden="true"><i class="ri-code-s-slash-line"></i></span>
      <span class="brand__name">{{ $brand }}</span>
    </a>

    <div class="header__right">
      <div class="pill" aria-label="Availability">
        <span class="pill__dot" aria-hidden="true"></span>
        <span>{{ $availableText }}</span>
      </div>

      <button
        class="iconCircle"
        id="menuBtn"
        type="button"
        aria-label="Open menu"
        aria-controls="navOverlay"
        aria-expanded="false"
      >
        <span class="hamb" aria-hidden="true"></span>
        <span class="hamb" aria-hidden="true"></span>
        <span class="hamb" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</header>

<div class="navOverlay" id="navOverlay" aria-hidden="true">
  <div class="navOverlay__inner">
    <a class="brand navOverlay__brand" href="#top" aria-label="Home">
      <span class="brand__mark" aria-hidden="true"><i class="ri-code-s-slash-line"></i></span>
      <span class="brand__name">{{ $brand }}</span>
    </a>

    <div class="navOverlay__topRight">
      <div class="pill" aria-label="Availability">
        <span class="pill__dot" aria-hidden="true"></span>
        <span>{{ $availableText }}</span>
      </div>

      <button
        class="iconCircle iconCircle--close"
        id="closeBtn"
        type="button"
        aria-label="Close menu"
      >
        <span class="xbar" aria-hidden="true"></span>
        <span class="xbar" aria-hidden="true"></span>
      </button>
    </div>

    <nav class="navOverlay__menu" aria-label="Overlay menu">
      @foreach($links as $l)
        <a class="navOverlay__link" href="{{ $l['href'] }}">{{ $l['label'] }}</a>
      @endforeach
    </nav>

    <div class="navOverlay__bottom">
      <div class="navOverlay__contact" aria-label="Contact">
        <a href="mailto:{{ $email }}">{{ $email }}</a>
        <span class="dotsep" aria-hidden="true">•</span>
        <a href="tel:{{ $tel }}">{{ $phone }}</a>
      </div>

      <div class="navOverlay__social" aria-label="Social links">
        @foreach($social as $s)
          <a href="{{ $s['href'] }}" aria-label="{{ $s['label'] }}">
            <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  <button
    class="navOverlay__backdrop"
    type="button"
    data-close="true"
    aria-label="Close overlay"
  ></button>
</div>
