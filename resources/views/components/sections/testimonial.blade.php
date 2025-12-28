@props([
  'id' => 'testimonial',
  'title' => 'Client Reviews',
  'subtitle' => "Based on reviews from over\n500 customers",

  'badgeImage' => 'images/section/review-customer.png',

  // data testimoni (opsional override dari parent)
  // [
  //   'title' => 'Excellent quality',
  //   'rating' => 5,
  //   'text' => '...',
  //   'name' => 'Client Name',
  //   'role' => 'CEO',
  //   'avatar' => 'https://...' atau 'images/avatar/avatar-1.png' (opsional)
  //   'avatar_id' => 45 (opsional, untuk pravatar)
  // ]
  'items' => null,

  'defaultAvatarId' => 45,
])

@php
  use Illuminate\Support\Str;

  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  $subtitleHtml = nl2br(e($subtitle));
  $badge = $src($badgeImage);

  $data = $items ?? [
    [
      'title' => 'Excellent quality',
      'rating' => 5,
      'text' => 'Great communication, fast delivery, and solid code quality. Highly recommended.',
      'name' => 'Client Name',
      'role' => 'CEO',
      // kalau avatar kosong -> pakai pravatar
      'avatar_id' => 45,
    ],
    [
      'title' => 'Outstanding support',
      'rating' => 5,
      'text' => 'Very responsive and detail-oriented. The result matched the design perfectly.',
      'name' => 'Client Name',
      'role' => 'Founder',
      'avatar_id' => 12,
    ],
    [
      'title' => 'Professional work',
      'rating' => 5,
      'text' => 'Clean implementation, performance is great, and the handover was smooth.',
      'name' => 'Client Name',
      'role' => 'Product Manager',
      'avatar_id' => 32,
    ],
  ];

  $dataTop = $data;
  $dataBottom = array_reverse($data);

  // helper avatar: prioritas avatar (manual) -> pravatar (avatar_id) -> defaultAvatarId
  $avatarUrl = function($t) use ($src, $defaultAvatarId) {
    if (!empty($t['avatar'])) return $src($t['avatar']);
    $id = (int)($t['avatar_id'] ?? $defaultAvatarId);
    $id = max(1, min(70, $id));
    return "https://i.pravatar.cc/120?img={$id}";
  };

  $renderCard = function($t) use ($avatarUrl) {
    $rating = max(0, min(5, (int)($t['rating'] ?? 5)));
    $tTitle = $t['title'] ?? 'Review';
    $text   = $t['text'] ?? '';
    $name   = $t['name'] ?? 'Client';
    $role   = $t['role'] ?? '';
    $avatar = $avatarUrl($t);
@endphp
    <div class="testimonial-item style-2">
      <div class="heading">
        <h6 class="mb_7">{{ $tTitle }}</h6>

        <ul class="ratings d-flex align-items-center gap_4" aria-label="Rating {{ $rating }} out of 5">
          @for($i=0; $i < $rating; $i++)
            <li><i class="ri-star-fill" aria-hidden="true"></i></li>
          @endfor
          @for($i=$rating; $i < 5; $i++)
            <li><i class="ri-star-line" aria-hidden="true"></i></li>
          @endfor
        </ul>
      </div>

      <p class="text_white font2">{{ $text }}</p>

      <div class="author">
        <div class="avatar">
          <img src="{{ $avatar }}" width="48" height="48" alt="{{ $name }}" loading="lazy" decoding="async">
        </div>

        <div class="info">
          <div class="name text-title text_white mb_2">{{ $name }}</div>
          @if(!empty($role))
            <div class="text-caption-2">{{ $role }}</div>
          @endif
        </div>
      </div>
    </div>
@php
  };
@endphp

<section id="{{ $id }}" class="section-testimonials-2 tf-spacing-4 section" aria-label="Testimonials">
  <div class="heading-section mb_57">
    <h1 class="title text-center mb_21 split-text effect-blur-fade">{{ $title }}</h1>

    <div class="d-flex align-items-center gap_13 justify-content-center animateFade">
      @if($badge)
        <img src="{{ $badge }}" alt="reviews" loading="lazy" decoding="async">
      @endif
      <p class="font2 text_white">{!! $subtitleHtml !!}</p>
    </div>
  </div>

  {{-- ROW 1 (left) --}}
  <div class="wrap-testimonial mb_15">
    <div class="infiniteslide" data-clone="2" data-style="left">
      @foreach($dataTop as $t)
        @php $renderCard($t); @endphp
      @endforeach
    </div>
  </div>

  {{-- ROW 2 (right) --}}
  <div class="wrap-testimonial">
    <div class="infiniteslide" data-clone="3" data-style="right">
      @foreach($dataBottom as $t)
        @php $renderCard($t); @endphp
      @endforeach
    </div>
  </div>
</section>
