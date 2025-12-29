@php
  $brand = $brand ?? 'Bagus Setiawan';

  $contact = $contact ?? [
    'email' => 'hello@bagussetiawan.com',
    'phone' => '(+62) 895-6288-94070',
  ];

  $hero = $hero ?? [
    'role' => 'Frontend Developer',
    'location' => 'Based in Indonesia',
    'first_name' => 'Bagus',
    'last_name' => 'Setiawan',
    'image' => 'images/hero/hero-img.png',
  ];

  $navLinks = $navLinks ?? [
    ['label' => 'About us',        'href' => '#about'],
    ['label' => 'Our Services',    'href' => '#service'],
    ['label' => 'Latest Work',     'href' => '#latest-work'],
    ['label' => 'Portfolio',       'href' => '#work'],
    ['label' => 'Client Reviews',  'href' => '#testimonial'],
  ];
@endphp

<x-layouts.app
  :title="'Portfolio — ' . $brand"
  description="Welcome to my portfolio website! I'm Bagus Setiawan, a passionate Frontend Developer based in Indonesia. Explore my projects and get in touch for collaborations."
>
  <x-sections.navbar
    :brand="$brand"
    :available-text="'Available for work'"
    :email="$contact['email']"
    :phone="$contact['phone']"
    :links="$navLinks"
  />

  <x-sections.hero
    :role="$hero['role']"
    :location="$hero['location']"
    :first-name="$hero['first_name']"
    :last-name="$hero['last_name']"
    image-url="images/hero/hero-img.png"
  />

  <div class="main-content section-onepage">
    <x-sections.about
      :years="3"
      role-line1="FRONTEND DEVELOPER"
      role-line2="DEVELOPER BASED IN INDONESIA"
    />

    <x-sections.section-about />

    {{-- ambil dari DB (controller) --}}
    <x-sections.our-services :services="$services" />

    {{-- latest work dari DB --}}
    <x-sections.latest-project id="latest-work" :projects="$latestProjects" />

    <x-sections.wrap-banner />

    {{-- portfolio dari DB --}}
    <x-sections.section-portfolio id="work" :items="$portfolioProjects" />

    <x-sections.testimonial :items="$testimonials ?? null" />
  </div>

  <x-sections.footer
    :brand="$brand"
    :email="$contact['email']"
    :phone="$contact['phone']"
  />

  <x-sections.order-modal />
</x-layouts.app>
