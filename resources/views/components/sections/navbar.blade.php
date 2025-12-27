@props([
  'brand' => 'Bagus Setiawan',
  'logo' => null,

  'email' => 'bagussetiawan.lz24@gmail.com',
  'phone' => '0895-6288-94070',
  'availableText' => 'Available for work',

  'links' => [
    ['label' => 'About us', 'href' => '#about'],
    ['label' => 'Our Services', 'href' => '#service'],
    ['label' => 'Latest Work', 'href' => '#work'],
    ['label' => 'Client Reviews', 'href' => '#testimonial'],
  ],

  'social' => [
    ['label' => 'Github', 'href' => 'https://github.com/BagusSetiawan09/', 'icon' => 'ri-github-line'],
    ['label' => 'Dribbble', 'href' => 'https://dribbble.com/bagussetiawann', 'icon' => 'ri-dribbble-line'],
    ['label' => 'Behance', 'href' => 'https://www.behance.net/bagussetiawann', 'icon' => 'ri-behance-line'],
    ['label' => 'Linkedin', 'href' => 'https://www.linkedin.com/in/bagussetiawann/', 'icon' => 'ri-linkedin-line'],
    ['label' => 'Instagram', 'href' => 'https://www.instagram.com/bagus.c07/', 'icon' => 'ri-instagram-line'],
  ],
])

@php
  use Illuminate\Support\Str;

  $tel = preg_replace('/[^0-9+]/', '', $phone);

  $logoSrc = null;
  if (!empty($logo)) {
    $logoSrc = Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset($logo);
  }
@endphp

<header class="header-2 header-fixed" id="top" aria-label="Header">
  <div class="container-2 d-flex justify-content-between">
    <div class="header-left logo">
      <a href="#top" class="text-white" aria-label="Home">
        @if($logoSrc)
          <img src="{{ $logoSrc }}" alt="{{ $brand }}">
        @else
          {{-- <span class="text-white">{{ $brand }}</span> --}}
          <span class="brand-text">{{ $brand }}</span>
        @endif
      </a>
    </div>

    <div class="header-right d-flex gap_8">
      <div class="tag-title d-inline-flex align-items-center gap_8 md-hide" aria-label="Availability">
        <span class="point" aria-hidden="true"></span>
        <p class="text-body-2 text_white">{{ $availableText }}</p>
      </div>

      <a
        href="#"
        class="link-no-action side-toggle"
        aria-label="Open menu"
        aria-controls="sideMenu"
        aria-expanded="false"
      >
        <div class="icon" aria-hidden="true">
          <span class="top"></span>
          <span class="middle"></span>
          <span class="bottom"></span>
        </div>
      </a>
    </div>
  </div>
</header>

<div class="side-menu-mobile" id="sideMenu" aria-hidden="true">
  <div class="tf-container h-100">
    <div class="menu-content h-100">
      {{-- <button type="button" class="side-info-close link-no-action" aria-label="Close menu">
        <i class="ri-close-line" aria-hidden="true"></i>
      </button> --}}

      <div class="menu-body">
        <ul class="nav-menu-list text-center">
          @foreach($links as $l)
            <li>
              <a href="{{ $l['href'] }}" class="menu-link link nav_link hover-line-text">
                {{ $l['label'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="menu-footer d-flex justify-content-between">
        <div class="menu-bot_left flex-wrap">
          <a class="h6 text-white link" href="mailto:{{ $email }}">{{ $email }}</a>
          <span class="br-dot" aria-hidden="true"></span>
          <a class="h6 text-white link" href="tel:{{ $tel }}">{{ $phone }}</a>
        </div>

        <div class="menu-bot_right">
          <ul class="tf-social-icon">
            @foreach($social as $s)
              <li>
                <a href="{{ $s['href'] }}" aria-label="{{ $s['label'] }}">
                  <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="offcanvas-overlay" aria-hidden="true"></div>
