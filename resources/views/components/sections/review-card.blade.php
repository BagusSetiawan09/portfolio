@props(['r'])

<article class="reviewCard">
  <div class="reviewCard__top">
    <h3 class="reviewCard__title">{{ $r['title'] }}</h3>

    <div class="reviewCard__stars" aria-label="{{ $r['stars'] }} stars">
      @for($i = 0; $i < $r['stars']; $i++)
        <i class="ri-star-fill" aria-hidden="true"></i>
      @endfor
    </div>
  </div>

  <p class="reviewCard__text">{{ $r['text'] }}</p>

  <div class="reviewCard__who">
    <img
      class="reviewCard__avatar"
      src="{{ $r['avatar'] }}"
      alt="Photo of {{ $r['name'] }}"
      loading="lazy"
    >
    <div class="reviewCard__whoText">
      <div class="reviewCard__name">{{ $r['name'] }}</div>
      <div class="reviewCard__role">{{ $r['role'] }}</div>
    </div>
  </div>
</article>
