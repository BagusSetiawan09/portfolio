@props([
  'label' => 'Back to top',
])

<button type="button" class="toTop" data-to-top aria-label="{{ $label }}">
  <span class="toTop__icon" aria-hidden="true">
    <i class="ri-arrow-up-line"></i>
  </span>

  <svg class="toTop__ring" viewBox="0 0 100 100" aria-hidden="true" focusable="false">
    <circle class="toTop__track" cx="50" cy="50" r="46"></circle>
    <circle class="toTop__progress" cx="50" cy="50" r="46"></circle>
  </svg>
</button>
