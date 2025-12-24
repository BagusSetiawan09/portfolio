@php
  $brand = 'Bagus Setiawan';

  $contact = [
    'email' => 'hello@bagussetiawan.com',
    'phone' => '(+62) 895-6288-94070',
  ];

  $hero = [
    'role' => 'Frontend Developer',
    'location' => 'Based in Indonesia',
    'first_name' => 'Bagus',
    'last_name' => 'Setiawan',
    'image' => 'https://images.unsplash.com/photo-1522252234503-e356532cafd5?auto=format&fit=crop&w=1400&q=80',
  ];
@endphp

<x-layouts.app title="Portfolio — Bagus Setiawan" description="Welcome to my portfolio website! I'm Bagus Setiawan, a passionate Frontend Developer based in Indonesia. Explore my projects and get in touch for collaborations.">
  <x-bg />

  <x-navbar
    :brand="$brand"
    :email="$contact['email']"
    :phone="$contact['phone']"
  />

  <main>
    <x-hero
      :role="$hero['role']"
      :location="$hero['location']"
      :first-name="$hero['first_name']"
      :last-name="$hero['last_name']"
      :image-url="$hero['image']"
    />

    <x-marquee />

    <x-about />
    <x-about-cover :image="$hero['image']" />

    <x-services />

    <x-latest-projects />

    <x-reviews />

    <x-footer
      :brand="$brand"
      :email="$contact['email']"
      :phone="$contact['phone']"
    />
  </main>

  <x-to-top />
</x-layouts.app>
