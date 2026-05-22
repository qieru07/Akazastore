<?php
require_once __DIR__ . '/helper.php';

$api_base = get_api_base_url();
$url = $api_base . "/games";
$response = @file_get_contents($url);
$result = json_decode($response, true);

$games = [];
if ($result && isset($result['data'])) {
    $games = $result['data'];
}
session_start(); 
// Ambil data banner dari API
$banner_url = $api_base . "/banners";
$banner_res = @file_get_contents($banner_url);
$banner_data = json_decode($banner_res, true);
$banners = $banner_data['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>GameTop — Top-up & Services</title>
  <script>
    (function() {
      const saved = localStorage.getItem('akaza_theme') || 'dark';
      document.documentElement.setAttribute('data-theme', saved);
      if (saved === 'dark') document.documentElement.classList.add('dark');
    })();
  </script>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
  <header class="navbar">
    <div class="nav-inner">
      <div class="brand">
        <div class="brand-icon">
          <img src="asset/img/akazachibi.png" alt="Logo">
        </div>
        <div class="brand-text">
          <div class="brand-name">AKAZASTORE</div>
          <div class="brand-sub">Top-up & Services</div>
        </div>
      </div>

      <div class="search-wrap">
        <input id="search" type="search" placeholder="Cari Game atau Voucher..." />
      </div>

      <nav class="nav-links">
          <a href="#">Topup</a>
          <a href="riwayat.php">Cek Transaksi</a>
          <a href="#">Kalkulator</a>

          <?php if(isset($_SESSION['username'])): ?>
              <a class="auth" href="dashboard_user.php" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 4px 12px;">👤 Akun: <?= $_SESSION['username']; ?></a>
              <a class="auth" href="auth/logout.php">Logout</a>
          <?php else: ?>
              <a class="auth" href="auth/login.php">Masuk</a>
              <a class="auth" href="auth/registrasi.php">Daftar</a>
          <?php endif; ?>
          <span id="userDisplay" class="user-display"></span>
        </nav>

      <button class="theme-toggle" id="themeToggle" aria-label="Toggle tema gelap/terang">
        <span class="icon-sun">☀️</span>
        <span class="icon-moon">🌙</span>
      </button>

      <button class="hamburger" id="hamburgerBtn" aria-label="Buka menu navigasi">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </header>

  <div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    <div class="mobile-nav-drawer">
      <div class="mobile-nav-header">
        <span class="brand-name">AKAZASTORE</span>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Tutup menu">✕</button>
      </div>
      <div class="mobile-nav-links">
        <a href="#">🎮 Topup</a>
        <a href="riwayat.php">📋 Cek Transaksi</a>
        <a href="#">🧮 Kalkulator</a>
        <div class="auth-section">
          <div class="auth-label">Akun</div>
          <?php if(isset($_SESSION['username'])): ?>
              <a href="dashboard_user.php">👤 Akun: <?= $_SESSION['username']; ?></a>
              <a href="auth/logout.php">🚪 Logout</a>
          <?php else: ?>
              <a href="auth/login.php">🔑 Masuk</a>
              <a href="auth/registrasi.php">📝 Daftar</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <main>
    <section class="hero" style="margin-top: 2rem;">
      <div class="container">
        
        <!-- Swiper Carousel (Banner Video & Foto) -->
        <div class="swiper heroSwiper" style="border-radius: 16px; overflow: hidden; width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
          <div class="swiper-wrapper">
              
              <?php if(!empty($banners)): ?>
                <?php foreach ($banners as $b): ?>
                  <div class="swiper-slide">
                      <?php if(isset($b['type']) && $b['type'] === 'video'): ?>
                        <div class="banner-slide" style="position: relative; width: 100%; padding-top: 35%; overflow: hidden; background: #000;">
                            <video autoplay muted loop playsinline 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                <source src="<?= $b['video_url']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                      <?php else: ?>
                        <div class="banner-slide" style="position: relative; width: 100%; padding-top: 35%; background: #1e293b;">
                          <img src="<?= $b['image_url']; ?>" alt="<?= $b['title']; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        </div>
                      <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- Fallback Slide jika kosong -->
                <div class="swiper-slide">
                    <div class="banner-slide" style="position: relative; width: 100%; padding-top: 35%; background: #1e293b; display: flex; align-items: center; justify-content: center; color: #fff;">
                        <h2>Selamat Datang di AkazaStore</h2>
                    </div>
                </div>
              <?php endif; ?>

          </div>
          <!-- Navigasi Swiper -->
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section>
    
    <style>
      .heroSwiper .swiper-button-next, .heroSwiper .swiper-button-prev { color: #fff !important; text-shadow: 0 2px 4px rgba(0,0,0,0.8); }
      .heroSwiper .swiper-pagination-bullet { background: #fff !important; opacity: 0.5; }
      .heroSwiper .swiper-pagination-bullet-active { background: #ef4444 !important; opacity: 1; }
      @media (max-width: 768px) {
          .banner-slide { padding-top: 50% !important; }
      }
    </style>
    <section class="section popular">
      <div class="section-header">
        <span class="fire">🔥</span>
        <h2>POPULER SEKARANG!</h2>
        <p class="section-subtitle">Berikut adalah beberapa produk yang paling populer saat ini.</p>
      </div>

        <div class="product-grid" id="productGrid">
            <?php if(!empty($games)): ?>
              <?php foreach ($games as $game): ?>
                  <?php $slug = $game['slug'] ?? 'item'; ?>
                  <a href="topup/<?= $slug; ?>.php?id=<?= $game['id']; ?>" class="prod-card">
                      <img src="<?= $game['thumbnail_url']; ?>" alt="<?= $game['name']; ?>" onerror="this.src='asset/placeholder-rect.png'"/>
                      <div class="prod-body">
                          <h3><?= $game['name']; ?></h3>
                          <p class="muted">Top-up <?= $game['name']; ?> resmi dan instan.</p>
                      </div>
                  </a>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </section>
  </main>

  
  <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
  </button>

  
  <button id="csBtn" class="cs-btn">CUSTOMER SERVICE</button>
<div id="userArea"></div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.heroSwiper', {
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      }
    });
  });
</script>

  <script src="script.js"></script>
</body>
</html>
