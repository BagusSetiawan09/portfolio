@props([
  'id' => 'testimonial',
  'title' => 'Client Reviews',
  'subtitle' => "Based on reviews from over\n500 customers",
  'badgeImage' => 'images/section/review-customer.png',
  'items' => null,
])

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Collection;
  use Illuminate\Database\Eloquent\Builder;
  use Illuminate\Database\Eloquent\Relations\Relation;
  use Illuminate\Pagination\AbstractPaginator;

  $src = function($v){
    if (empty($v)) return '';
    if (Str::startsWith($v, ['http://','https://'])) return $v;
    if (Str::startsWith($v, 'images/')) return asset('assets/template/' . $v);
    return asset($v);
  };

  $subtitleHtml = nl2br(e($subtitle));
  $badge = $src($badgeImage);

  // DEFAULT (tampil kalau DB kosong)
  $default = [
    [
      'title' => 'Excellent quality',
      'rating' => 5,
      'text' => 'Great communication, fast delivery, and solid code quality. Highly recommended.',
      'name' => 'Client Name',
      'role' => 'CEO',
      'avatar' => 'https://i.pravatar.cc/120?img=45',
    ],
    [
      'title' => 'Outstanding support',
      'rating' => 5,
      'text' => 'Very responsive and detail-oriented. The result matched the design perfectly.',
      'name' => 'Client Name',
      'role' => 'Founder',
      'avatar' => 'https://i.pravatar.cc/120?img=12',
    ],
    [
      'title' => 'Professional work',
      'rating' => 5,
      'text' => 'Clean implementation, performance is great, and the handover was smooth.',
      'name' => 'Client Name',
      'role' => 'Product Manager',
      'avatar' => 'https://i.pravatar.cc/120?img=22',
    ],
  ];

  // NORMALIZE ITEMS -> ARRAY
  $raw = $items;

  if ($raw instanceof AbstractPaginator) {
    $raw = $raw->items();                 // array
  } elseif ($raw instanceof Collection) {
    $raw = $raw->all();                   // array
  } elseif ($raw instanceof Builder || $raw instanceof Relation) {
    $raw = $raw->get()->all();            // array
  } elseif (is_null($raw)) {
    $raw = [];
  } elseif (!is_array($raw)) {
    $raw = [];
  }

  // MAP ke format yang pasti
  $data = [];
  foreach ($raw as $row) {

    // ambil avatar_url (dari DB/Filament), fallback ke avatar (kalau ada dari array lama)
    $avatarVal = (string) data_get($row, 'avatar_url', data_get($row, 'avatar', ''));

    // kalau masih kosong, pakai avatar random tapi konsisten berdasarkan email/nama
    if (blank($avatarVal)) {
      $seed = (string) data_get($row, 'email', data_get($row, 'name', 'client'));
      $avatarVal = 'https://i.pravatar.cc/120?u=' . urlencode($seed);
    }

    $data[] = [
      'title'  => data_get($row, 'title', 'Excellent quality'),
      'rating' => (int) data_get($row, 'rating', 5),
      'text'   => (string) data_get($row, 'text', ''),
      'name'   => data_get($row, 'name', 'Client Name'),
      'role'   => data_get($row, 'role', 'CEO'),
      'avatar' => $avatarVal,
    ];
  }

  // buang item yang text-nya kosong
  $data = array_values(array_filter($data, fn($t) => trim($t['text']) !== ''));

  if (count($data) === 0) {
    $data = $default;
  }
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

  <div class="wrap-testimonial mb_15">
    <div class="infiniteslide" data-clone="2" data-style="left">
      @foreach($data as $t)
        @php
          $rating = max(0, min(5, (int)($t['rating'] ?? 5)));
          $avatar = $src($t['avatar'] ?? '');
          $tTitle = $t['title'] ?? 'Review';
          $text   = $t['text'] ?? '';
          $name   = $t['name'] ?? 'Client Name';
          $role   = $t['role'] ?? '';
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
            <div class="avatar" style="width:48px;height:48px;border-radius:999px;overflow:hidden;flex:0 0 48px;">
              @if($avatar)
                <img
                  src="{{ $avatar }}"
                  width="48"
                  height="48"
                  alt="{{ $name }}"
                  loading="lazy"
                  decoding="async"
                  style="width:100%;height:100%;object-fit:cover;display:block;"
                >
              @endif
            </div>

            <div class="info">
              <div class="name text-title text_white mb_2">{{ $name }}</div>
              @if(!empty($role))
                <div class="text-caption-2">{{ $role }}</div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="wrap-testimonial mb_15">
    <div class="infiniteslide" data-clone="2" data-style="right">
      @foreach($data as $t)
        @php
          $rating = max(0, min(5, (int)($t['rating'] ?? 5)));
          $avatar = $src($t['avatar'] ?? '');
          $tTitle = $t['title'] ?? 'Review';
          $text   = $t['text'] ?? '';
          $name   = $t['name'] ?? 'Client Name';
          $role   = $t['role'] ?? '';
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
            <div class="avatar" style="width:48px;height:48px;border-radius:999px;overflow:hidden;flex:0 0 48px;">
              @if($avatar)
                <img
                  src="{{ $avatar }}"
                  width="48"
                  height="48"
                  alt="{{ $name }}"
                  loading="lazy"
                  decoding="async"
                  style="width:100%;height:100%;object-fit:cover;display:block;"
                >
              @endif
            </div>

            <div class="info">
              <div class="name text-title text_white mb_2">{{ $name }}</div>
              @if(!empty($role))
                <div class="text-caption-2">{{ $role }}</div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</section>
