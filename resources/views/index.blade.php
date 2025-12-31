@php
  use App\Models\Site;
  use App\Models\Profile;
  use Illuminate\Support\Str;

  // 1. AMBIL DATA DARI DATABASE
  $site    = Site::first();
  $profile = Profile::first();

  // 2. SIAPKAN VARIABEL
  $brandName = $site->site_name ?? ($profile->name ?? 'Bagus Setiawan');
  
  // Pecah Nama untuk Hero
  $fullName  = $profile->name ?? 'Bagus Setiawan';
  $parts     = explode(' ', $fullName);
  $firstName = $parts[0];
  $lastName  = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

  // Data Contact
  $contactEmail = $profile->email ?? 'hello@example.com';
  $contactPhone = $profile->whatsapp ?? '+62 812 3456 7890';

  // Data Hero (Teks tetap dinamis dari DB)
  $heroRole   = $profile->role ?? 'Frontend Developer';
  $heroDesc   = $profile->hero_description ?? 'Welcome to my portfolio website!';
  
  // --- PERBAIKAN: FOTO HERO DI-HARDCODE (AGAR TIDAK BERUBAH) ---
  // Saya kembalikan ke path original Anda, abaikan data dari DB.
  $heroImage  = 'images/hero/hero-img.png'; 
  // -----------------------------------------------------------

  // Nav Links
  $navLinks = $navLinks ?? [
    ['label' => 'About us',        'href' => '#about'],
    ['label' => 'Our Services',    'href' => '#service'],
    ['label' => 'Latest Work',     'href' => '#latest-work'],
    ['label' => 'Portfolio',       'href' => '#work'],
    ['label' => 'Client Reviews',  'href' => '#testimonial'],
  ];
@endphp

<x-layouts.app
  :title="' ' . $brandName"
  :description="$heroDesc"
>
  {{-- NAVBAR --}}
  <x-sections.navbar
    :brand="$brandName"
    :available-text="$profile->is_available ? 'Available for work' : 'Busy'"
    :email="$contactEmail"
    :phone="$contactPhone"
    :links="$navLinks"
  />

  {{-- HERO SECTION --}}
  <x-sections.hero
    :role="$heroRole"
    location="Based in Indonesia" 
    :first-name="$firstName"
    :last-name="$lastName"
    :image-url="$heroImage" {{-- <-- Ini sekarang akan selalu memakai gambar original --}}
    :description="$heroDesc"
  />

  <div class="main-content section-onepage">
    <x-sections.about
      :years="3"
      :role-line1="strtoupper($heroRole)"
      role-line2="BASED IN INDONESIA"
    />

    <x-sections.section-about />

    <x-sections.our-services :services="$services" />

    <x-sections.latest-project id="latest-work" :projects="$latestProjects" />

    <x-sections.wrap-banner />

    <x-sections.section-portfolio id="work" :items="$portfolioProjects" />

    <x-sections.testimonial :items="$testimonials ?? null" />
  </div>

  <x-sections.footer
    :brand="$brandName"
    :email="$contactEmail"
    :phone="$contactPhone"
  />

  <x-sections.order-modal />
</x-layouts.app>