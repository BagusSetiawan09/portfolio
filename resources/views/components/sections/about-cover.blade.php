@props([
  'image' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=2000&q=80',
  'alt' => null,
  'parallaxStrength' => 22,
])

<section class="aboutCover" aria-label="About cover">
  <div class="container">
    <div
      class="aboutCover__media reveal"
      data-parallax
      data-parallax-strength="{{ $parallaxStrength }}"
    >
      <img
        src="{{ $image }}"
        alt="{{ $alt ?? 'About cover image' }}"
        loading="lazy"
        decoding="async"
      >
    </div>
  </div>
</section>
