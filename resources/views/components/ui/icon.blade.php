@props([
  'name',        // contoh: ri-arrow-right-up-line
  'size' => null, // contoh: 18 atau '18px'
])

@php
  $style = $size ? 'font-size:' . (is_numeric($size) ? $size.'px' : $size) . ';' : null;
@endphp

<i {{ $attributes->merge(['class' => $name])->merge($style ? ['style' => $style] : []) }} aria-hidden="true"></i>
