@php
  $reviews = [
    [
      'title' => 'Performance, speed and functionality',
      'stars' => 5,
      'text'  => 'The web app is incredibly fast and the code is very clean. He managed to turn complex requirements into a high-performance reality. Highly recommended for any technical project!',
      'name'  => 'Annette Black',
      'role'  => 'CEO Themesflat',
      'avatar'=> 'https://i.pravatar.cc/120?img=32',
    ],
    [
      'title' => 'Ease of use and attractive appearance',
      'stars' => 5,
      'text'  => 'Outstanding attention to detail. The prototype was intuitive and the user flow felt very natural. He really understands how to balance aesthetics with usability.',
      'name'  => 'Cody Fisher',
      'role'  => 'Product Lead',
      'avatar'=> 'https://i.pravatar.cc/120?img=12',
    ],
    [
      'title' => 'Ease of management & speed of setup',
      'stars' => 5,
      'text'  => 'I love how easy it is for me to manage my own content now. The setup was seamless, and the site is fully responsive. A perfect solution for my business!',
      'name'  => 'Kristin Watson',
      'role'  => 'Founder',
      'avatar'=> 'https://i.pravatar.cc/120?img=5',
    ],
    [
      'title' => 'Creativity & visual identity',
      'stars' => 5,
      'text'  => 'Clean, modern, and perfectly aligned with our brand identity. The communication was fast, and he delivered the final assets exactly as we envisioned. Great work!',
      'name'  => 'Wade Warren',
      'role'  => 'CTO',
      'avatar'=> 'https://i.pravatar.cc/120?img=19',
    ],
    [
      'title' => 'Technical accuracy & quality of results',
      'stars' => 5,
      'text'  => 'Professional execution from start to finish. The application is robust, bug-free, and handles data perfectly. A top-tier developer who knows his craft.',
      'name'  => 'Jenny Wilson',
      'role'  => 'Marketing',
      'avatar' => 'https://i.pravatar.cc/120?img=45',
    ],
  ];

  $reviews2 = array_reverse($reviews);

  $renderReviews = function($items) {
    foreach ($items as $r) {
      echo view('components.review-card', ['r' => $r])->render();
    }
  };
@endphp

<section class="reviews" id="reviews" aria-label="Client Reviews">
  <div class="container">
    <header class="reviews__head">
      <h2 class="reviews__title">Client Reviews</h2>

      <div class="reviews__rating">
        <div class="reviews__score">4.9</div>
        
        <div class="reviews__ratingText">
          <div class="reviews__ratingSmall">Based on reviews from over</div>
          <div class="reviews__ratingBig">500 customers</div>
        </div>
      </div>
    </header>
  </div>

  <div class="reviewsMarquee" data-marquee>
    {{-- ATAS: kanan -> kiri --}}
    <div class="reviewsMarquee__line reviewsMarquee__line--rtl">
      <div class="reviewsMarquee__track">
        <div class="reviewsMarquee__group">
          @php($renderReviews($reviews))
        </div>
        <div class="reviewsMarquee__group" aria-hidden="true">
          @php($renderReviews($reviews))
        </div>
      </div>
    </div>

    {{-- BAWAH: kiri -> kanan --}}
    <div class="reviewsMarquee__line reviewsMarquee__line--ltr">
      <div class="reviewsMarquee__track">
        <div class="reviewsMarquee__group">
          @php($renderReviews($reviews2))
        </div>
        <div class="reviewsMarquee__group" aria-hidden="true">
          @php($renderReviews($reviews2))
        </div>
      </div>
    </div>
  </div>
</section>
