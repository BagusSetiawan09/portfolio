@php
  $services = [
    [
      'title' => 'Web App Development',
      'tags'  => ['Web Application', 'Dashboard', 'Landing Page', 'Performance'],
      'img'   => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1800&q=80',
      'href'  => '#contact',
    ],
    [
      'title' => 'UI/UX & Prototyping',
      'tags'  => ['UX Research', 'Wireframe', 'Figma', 'Design System'],
      'img'   => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1800&q=80',
      'href'  => '#contact',
    ],
    [
      'title' => 'CMS & Website Builder',
      'tags'  => ['WordPress', 'Headless', 'SEO', 'Maintenance'],
      'img'   => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1800&q=80',
      'href'  => '#contact',
    ],
    [
      'title' => 'Graphic Design',
      'tags'  => ['Branding', 'Social Media', 'Poster', 'Visual Identity'],
      'img'   => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1800&q=80',
      'href'  => '#contact',
    ],

  ];

  $projects = [
    [
      'title' => 'Website',
      'tags'  => ['Web Application','Laravel','API'],
      'year'  => now()->year,
      'img'   => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1600&q=80',
      'href'  => '#',
    ],
    [
      'title' => 'Wordpress',
      'tags'  => ['Landing Page','WordPress','SEO'],
      'year'  => now()->year,
      'img'   => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1600&q=80',
      'href'  => '#',
    ],
  ];

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
    'image' => 'images/hero/hero-img.png',
  ];

  $navLinks = [
    ['label' => 'About us',        'href' => '#about'],
    ['label' => 'Our Services',    'href' => '#service'],
    ['label' => 'Latest Work',     'href' => '#work'],
    ['label' => 'Client Reviews',  'href' => '#testimonial'],
  ];
@endphp

<x-layouts.app
  :title="'Portfolio — ' . $brand"
  description="Welcome to my portfolio website! I'm Bagus Setiawan, a passionate Frontend Developer based in Indonesia. Explore my projects and get in touch for collaborations."
>
  {{-- 1) NAVBAR --}}
  <x-sections.navbar
    :brand="$brand"
    :available-text="'Available for work'"
    :email="$contact['email']"
    :phone="$contact['phone']"
    :links="$navLinks"
  />

  {{-- 2) HERO --}}
  <x-sections.hero
    :role="$hero['role']"
    :location="$hero['location']"
    :first-name="$hero['first_name']"
    :last-name="$hero['last_name']"
    image-url="images/hero/hero-img.png"
  />

  {{-- 3) MARQUEE --}}
  {{-- <x-sections.marquee
    :name="$brand"
    :role="$hero['role']"
    :items-top="['© Bagus Setiawan','Frontend Developer','2025 portfolio']"
    :items-bottom="['Frontend Developer','2025 portfolio','© Bagus Setiawan']" 
  />--}}

  {{-- MAIN CONTENT --}}
  <div class="main-content section-onepage">
    <x-sections.about
      :years="3"
      role-line1="FRONTEND DEVELOPER"
      role-line2="DEVELOPER BASED IN INDONESIA"
    />

    <x-sections.section-about />

    <x-sections.our-services :services="$services" />

    {{-- samakan dengan nav #work --}}
    <x-sections.latest-project id="work" :projects="$projects" />

    <x-sections.wrap-banner />

    <x-sections.section-portfolio />

    <x-sections.testimonial />
  </div>

  {{-- FOOTER --}}
  <x-sections.footer
    :brand="$brand"
    :email="$contact['email']"
    :phone="$contact['phone']"
  />


</x-layouts.app>
