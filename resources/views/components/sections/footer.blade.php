@props([
  'brand' => 'Bagus Setiawan',
  'role' => 'Frontend Developer',
  'email' => 'hello@bagussetiawan.com',
  'phone' => '(+62) 895-6288-94070',
  'year' => null,

  'quote' => '“ I can help you build an impressive website that boosts business results. “',

  // bisa URL atau 'images/avatar/avatar-7.png'
  'avatar' => 'images/avatar/img-footer.png',

  // gambar dekor footer (opsional, bisa URL atau images/...)
  'footerImage' => 'images/section/footer.png',

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
    ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/in/bagussetiawann/', 'icon' => 'ri-linkedin-line'],
    ['label' => 'Instagram', 'href' => 'https://www.instagram.com/bagus.c07/', 'icon' => 'ri-instagram-line'],
  ],

  'metaRight' => 'Available for work',
])

@php
  use Illuminate\Support\Str;

  $y = $year ?? now()->year;
  $tel = preg_replace('/[^0-9+]/', '', $phone);

  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  $avatarSrc = $src($avatar);
  $footerImgSrc = $src($footerImage);
@endphp

<footer class="footer style-2" id="contact" aria-label="Footer">
  <div class="footer-body">
    <div class="tf-container">
      <div class="row">

        {{-- Quote + profile --}}
        <div class="col-lg-4 col-md-6 col-tes">
          <div class="tes-footer">
            <h6 class="desc">{{ $quote }}</h6>

            <div class="author d-flex align-items-center gap_16">
              <div class="avatar">
                @if($avatarSrc)
                  <img src="{{ $avatarSrc }}" width="64" height="64" alt="{{ $brand }}" loading="lazy" decoding="async">
                @endif
              </div>

              <div class="info">
                <h6 class="name text_white mb_2">{{ $brand }}</h6>
                <div class="text-title font2 text-color-1">{{ $role }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Contact --}}
        <div class="col-lg-4 col-md-6 col-contact">
          <div class="footer-contact">
            <div class="item mb_32">
              <p class="font2 mb_12">Please send me an email to</p>
              <a href="mailto:{{ $email }}" class="h4 link text_white hover-underline-link">
                {{ $email }}
              </a>
            </div>

            <div class="item">
              <p class="font2 mb_12">Let's talk!</p>
              <a href="tel:{{ $tel }}" class="h4 link text_white">
                {{ $phone }}
              </a>
            </div>
          </div>
        </div>

        {{-- Quick link + Social --}}
        <div class="col-lg-4 col-social">
          <div class="footer-social">

            <div class="wrap-quick-link">
              <div class="text-title font2 text-color-1 mb_12">Quick link</div>
              <ul class="d-grid gap_5" aria-label="Quick links">
                @foreach($links as $l)
                  <li>
                    <a href="{{ $l['href'] }}" class="text-title text_white link hover-line-text">
                      {{ $l['label'] }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>

            <div class="wrap-social">
              <div class="text-title font2 text-color-1 mb_12">Social</div>
              <ul class="tf-social" aria-label="Social links">
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
  </div>

  <div class="footer-bottom">
    <div class="tf-container">
      {{-- @if($footerImgSrc)
        <div class="mb_30 wow animate__animated animate__fadeInUp" data-wow-duration="2s" data-wow-delay="0s">
          <img src="{{ $footerImgSrc }}" alt="footer" loading="lazy" decoding="async">
        </div>
      @endif --}}
      <div class="mb_30 wow animate__animated animate__fadeInUp" data-wow-duration="2s" data-wow-delay="0s">
        <div class="footer-brandText">©Bagus Setiawan</div>
      </div>

      <div class="bot d-flex justify-content-between align-items-center">
        <p class="font2 text_white">© {{ $y }} {{ $brand }}. All rights reserved.</p>
        <span class="line" aria-hidden="true"></span>
        <p class="font2 text_white">{{ $y }} Portfolio - {{ $role }}</p>
        <span class="line" aria-hidden="true"></span>
        <p class="font2 text_white">{{ $metaRight }}</p>
      </div>
    </div>
  </div>
</footer>
