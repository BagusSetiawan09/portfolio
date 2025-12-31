@props([
  // Props ini sekarang jadi Fallback (Cadangan) saja
  'defaultLinks' => [
    ['label' => 'About us', 'url' => '#about'],
    ['label' => 'Our Services', 'url' => '#service'],
    ['label' => 'Latest Work', 'url' => '#work'],
    ['label' => 'Client Reviews', 'url' => '#testimonial'],
  ],
])

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  $p = $profile ?? null;

  // 1. DATA UTAMA
  $brand    = $p->name ?? 'Bagus Setiawan';
  $role     = $p->role ?? 'Frontend Developer';
  $email    = $p->email ?? 'hello@example.com';
  $phone    = $p->whatsapp ?? '628123456789';
  $quote    = $p->footer_quote ?? '“ I can help you build an impressive website that boosts business results. “';
  
  // 2. LABEL TEKS (Customizable)
  $labelEmail   = $p->email_label ?? 'Please send me an email to';
  $labelPhone   = $p->phone_label ?? "Let's talk!";
  $labelQuick   = $p->quick_link_title ?? 'Quick link';
  $labelSocial  = $p->social_title ?? 'Social';
  $copyright    = $p->copyright_text ?? '© ' . now()->year . ' ' . $brand . '. All rights reserved.';

  // 3. QUICK LINKS (Ambil dari Repeater atau Fallback)
  $footerLinks = $p && !empty($p->footer_links) ? $p->footer_links : $defaultLinks;

  // Helper Phone Link
  $telLink  = preg_replace('/[^0-9]/', '', $phone);
  if(Str::startsWith($telLink, '0')) $telLink = '62' . substr($telLink, 1);

  // Helper Avatar
  $avatarSrc = asset('assets/template/images/avatar/img-footer.png'); 
  if ($p && !empty($p->avatar_url)) {
      if (Str::startsWith($p->avatar_url, ['http://', 'https://'])) {
          $avatarSrc = $p->avatar_url;
      } else {
          $avatarSrc = Storage::url($p->avatar_url);
      }
  }

  // Social Media
  $social = [];
  if($p) {
      if($p->github)    $social[] = ['label' => 'Github', 'href' => 'https://github.com/'.$p->github, 'icon' => 'ri-github-line'];
      if($p->linkedin)  $social[] = ['label' => 'Linkedin', 'href' => 'https://linkedin.com/in/'.$p->linkedin, 'icon' => 'ri-linkedin-line'];
      if($p->instagram) $social[] = ['label' => 'Instagram', 'href' => 'https://instagram.com/'.$p->instagram, 'icon' => 'ri-instagram-line'];
  }
  
  $y = now()->year;
@endphp

<footer class="footer style-2" id="contact" aria-label="Footer">
  <div class="footer-body">
    <div class="tf-container">
      <div class="row">

        {{-- COL 1: Quote + Profile --}}
        <div class="col-lg-4 col-md-6 col-tes">
          <div class="tes-footer">
            <h6 class="desc">{{ $quote }}</h6>
            <div class="author d-flex align-items-center gap_16">
              <div class="avatar">
                 <img src="{{ $avatarSrc }}" width="64" height="64" alt="{{ $brand }}" 
                      style="border-radius: 50%; object-fit: cover; background-color: #222;" loading="lazy">
              </div>
              <div class="info">
                <h6 class="name text_white mb_2">{{ $brand }}</h6>
                <div class="text-title font2 text-color-1">{{ $role }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- COL 2: Contact --}}
        <div class="col-lg-4 col-md-6 col-contact">
          <div class="footer-contact">
            <div class="item mb_32">
              <p class="font2 mb_12">{{ $labelEmail }}</p>
              <a href="mailto:{{ $email }}" class="h4 link text_white hover-underline-link">
                {{ $email }}
              </a>
            </div>
            <div class="item">
              <p class="font2 mb_12">{{ $labelPhone }}</p>
              <a href="https://wa.me/{{ $telLink }}" target="_blank" class="h4 link text_white">
                {{ $phone }}
              </a>
            </div>
          </div>
        </div>

        {{-- COL 3: Quick Link + Social --}}
        <div class="col-lg-4 col-social">
          <div class="footer-social">
            
            {{-- Quick Links Dinamis --}}
            <div class="wrap-quick-link">
              <div class="text-title font2 text-color-1 mb_12">{{ $labelQuick }}</div>
              <ul class="d-grid gap_5" aria-label="Quick links">
                @foreach($footerLinks as $l)
                  <li>
                    {{-- Support format repeater ('label', 'url') atau fallback array lama --}}
                    <a href="{{ $l['url'] ?? $l['href'] }}" class="text-title text_white link hover-line-text">
                      {{ $l['label'] }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>

            <div class="wrap-social">
              <div class="text-title font2 text-color-1 mb_12">{{ $labelSocial }}</div>
              <ul class="tf-social" aria-label="Social links">
                @foreach($social as $s)
                  <li>
                    <a href="{{ $s['href'] }}" aria-label="{{ $s['label'] }}" target="_blank">
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
  </div>

  <div class="footer-bottom">
    <div class="tf-container">
      <div class="mb_30 wow animate__animated animate__fadeInUp" data-wow-duration="2s" data-wow-delay="0s">
        <div class="footer-brandText">©{{ $brand }}</div>
      </div>

      <div class="bot d-flex justify-content-between align-items-center">
        <p class="font2 text_white">{{ $copyright }}</p>
        <span class="line" aria-hidden="true"></span>
        <p class="font2 text_white">{{ $y }} Portfolio - {{ $role }}</p>
      </div>
    </div>
  </div>
</footer>