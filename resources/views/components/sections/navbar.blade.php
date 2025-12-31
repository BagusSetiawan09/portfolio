@props([
    'links' => [
        ['label' => 'About us', 'href' => '#about'],
        ['label' => 'Our Services', 'href' => '#service'],
        ['label' => 'Latest Work', 'href' => '#work'],
        ['label' => 'Client Reviews', 'href' => '#testimonial'],
    ],
])

@php
    use Illuminate\Support\Str;
    use App\Models\Site;
    use App\Models\Profile;

    // 1. AMBIL DATA DATABASE
    $site = Site::first();
    $p    = Profile::first();

    // 2. SETTING LOGO & BRAND
    $logoUrl  = $site->logo_url ?? null;
    $siteName = $site->site_name ?? 'Bagus Setiawan';
    $finalBrand = $p->name ?? $siteName; 

    // 3. SETTING KONTAK
    $email    = $p->email ?? 'hello@example.com';
    $whatsapp = $p->whatsapp ?? '628123456789';

    // Format WA Link
    $telLink = preg_replace('/[^0-9]/', '', $whatsapp);
    if(Str::startsWith($telLink, '0')) {
        $telLink = '62' . substr($telLink, 1);
    }

    // 4. STATUS AVAILABLE
    $isAvailable = $p ? $p->is_available : true;
    $statusText  = $isAvailable ? 'Available for work' : 'Busy';
    $statusColor = $isAvailable ? 'text_white' : 'text-danger';
    $dotColor    = $isAvailable ? '#00ff88' : '#ff4d4d';

    // 5. SOCIAL MEDIA
    $social = [];
    if($p) {
        if($p->github)    $social[] = ['label' => 'Github', 'href' => 'https://github.com/'.$p->github, 'icon' => 'ri-github-line'];
        if($p->linkedin)  $social[] = ['label' => 'Linkedin', 'href' => 'https://linkedin.com/in/'.$p->linkedin, 'icon' => 'ri-linkedin-line'];
        if($p->instagram) $social[] = ['label' => 'Instagram', 'href' => 'https://instagram.com/'.$p->instagram, 'icon' => 'ri-instagram-line'];
    }
    if(empty($social)) {
        $social[] = ['label' => 'Github', 'href' => '#', 'icon' => 'ri-github-line'];
    }

    // 6. LOGIC GAMBAR LOGO
    $finalLogo = null;
    if (!empty($logoUrl)) {
        $finalLogo = Str::startsWith($logoUrl, ['http://', 'https://']) ? $logoUrl : asset($logoUrl);
    } 
@endphp

<header class="header-2 header-fixed" id="top" aria-label="Header">
  <div class="container-2 d-flex justify-content-between align-items-center">
    
    {{-- LOGO AREA --}}
    <div class="header-left logo">
      <a href="/" class="text-white d-flex align-items-center text-decoration-none" aria-label="Home">
        @if(!empty($finalLogo))
          <img src="{{ $finalLogo }}" alt="{{ $finalBrand }}" style="max-height: 40px; width: auto;">
        @else
          <span class="brand-text h4 mb-0 font-weight-bold">{{ $finalBrand }}</span>
        @endif
      </a>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="header-right d-flex align-items-center gap_8">
      
      {{-- STATUS BADGE (Desktop) --}}
      <div class="tag-title d-none d-md-inline-flex align-items-center gap_8" aria-label="Availability">
        <span class="point" aria-hidden="true" 
              style="background-color: {{ $dotColor }}; width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
        <p class="text-body-2 mb-0 {{ $statusColor }}">{{ $statusText }}</p>
      </div>

      {{-- ========================================================
           PERBAIKAN: TOMBOL ORDER POP-UP (DATA-ORDER-OPEN)
           ======================================================== --}}
      <a
        href="#"
        class="link-no-action nav-order-btn"
        data-order-open {{-- <-- INI KUNCINYA AGAR POPUP MUNCUL --}}
        aria-label="Order Now"
        title="Order Now"
      >
        <i class="ri-shopping-bag-3-line" aria-hidden="true"></i>
      </a>

      {{-- HAMBURGER MENU --}}
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

{{-- MOBILE MENU SIDEBAR --}}
<div class="side-menu-mobile" id="sideMenu" aria-hidden="true">
  <div class="tf-container h-100">
    <div class="menu-content h-100 d-flex flex-column justify-content-between">
      
      {{-- Menu List --}}
      <div class="menu-body mt-5">
        <ul class="nav-menu-list text-center list-unstyled">
          @foreach($links as $l)
            <li class="mb-3">
              <a href="{{ $l['href'] }}" class="menu-link link nav_link h3 text-white hover-line-text text-decoration-none">
                {{ $l['label'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Menu Footer --}}
      <div class="menu-footer d-flex flex-column flex-md-row justify-content-between align-items-center pb-4">
        <div class="menu-bot_left d-flex flex-column flex-md-row gap-3 align-items-center mb-3 mb-md-0">
          <a class="h6 text-white link text-decoration-none" href="mailto:{{ $email }}">{{ $email }}</a>
          <span class="br-dot d-none d-md-block text-white" aria-hidden="true">•</span>
          <a class="h6 text-white link text-decoration-none" href="https://wa.me/{{ $telLink }}" target="_blank">{{ $whatsapp }}</a>
        </div>

        <div class="menu-bot_right">
          <ul class="tf-social-icon d-flex gap-3 list-unstyled mb-0">
            @foreach($social as $s)
              <li>
                <a href="{{ $s['href'] }}" aria-label="{{ $s['label'] }}" target="_blank" class="text-white h5 text-decoration-none">
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