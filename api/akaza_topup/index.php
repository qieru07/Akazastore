<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/auth_helper.php';

$logged_in_user = auth_check();

$games = [];
$games_query = mysqli_query($conn, "SELECT * FROM games WHERE status = 1");
if ($games_query) {
    while ($row = mysqli_fetch_assoc($games_query)) {
        // Dynamic correction for Roblox (broad match)
        $name_lower = strtolower($row['name'] ?? '');
        $slug_lower = strtolower($row['slug'] ?? '');
        if (strpos($name_lower, 'roblox') !== false || strpos($slug_lower, 'roblox') !== false || $slug_lower === 'rb') {
            $row['thumbnail'] = 'Roblox.png';
            @mysqli_query($conn, "UPDATE games SET thumbnail = 'Roblox.png' WHERE id = " . intval($row['id']));
        }
        
        $thumbnail = $row['thumbnail'] ?? '';
        if (str_starts_with($thumbnail, 'http')) {
            $row['thumbnail_url'] = $thumbnail;
        } else {
            $row['thumbnail_url'] = get_image_base_url() . '/games/' . $thumbnail;
        }
        $row['slug'] = $row['slug'] ?? 'item';
        $games[] = $row;
    }
}



$banners = [];
$banners_query = mysqli_query($conn, "SELECT * FROM banners WHERE status = 1");
if ($banners_query) {
    while ($row = mysqli_fetch_assoc($banners_query)) {
        $row['image_url'] = $row['image'] ? get_image_base_url() . '/banners/' . $row['image'] : null;
        $row['video_url'] = $row['video_url'] ? get_video_base_url() . '/banners/' . $row['video_url'] : null;
        $banners[] = $row;
    }
}
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

          <?php if($logged_in_user): ?>
              <a class="auth" href="dashboard_user.php" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 4px 12px;">👤 Akun: <?= $logged_in_user; ?></a>
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
          <?php if($logged_in_user): ?>
              <a href="dashboard_user.php">👤 Akun: <?= $logged_in_user; ?></a>
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
                      <img src="<?= $game['thumbnail_url']; ?>" alt="<?= $game['name']; ?>" onerror="this.src='asset/img/akazachibi.png'"/>
                      <div class="prod-body">
                          <h3><?= $game['name']; ?></h3>
                          <p class="muted">Top-up <?= $game['name']; ?> resmi dan instan.</p>
                      </div>
                  </a>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </section>

    <!-- ===== REVIEWS SECTION ===== -->
    <?php
    // Buat tabel jika belum ada
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `reviews` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `transaksi_id` INT NOT NULL,
        `username` VARCHAR(100) NOT NULL,
        `game` VARCHAR(100) NOT NULL,
        `rating` TINYINT(1) NOT NULL DEFAULT 5,
        `komentar` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Ambil total review & rata-rata rating
    $stats_res = mysqli_query($conn, "SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM reviews");
    $stats = mysqli_fetch_assoc($stats_res);
    $total_reviews = $stats['total'] ?? 0;
    $avg_rating    = round($stats['avg_rating'] ?? 0, 1);

    // Ambil 10 review terbaru
    $reviews_res = mysqli_query($conn, "SELECT * FROM reviews ORDER BY created_at DESC LIMIT 10");
    $reviews = [];
    if ($reviews_res) {
        while ($r = mysqli_fetch_assoc($reviews_res)) $reviews[] = $r;
    }
    ?>

    <?php if ($total_reviews > 0): ?>
    <section class="section reviews-section">
      <div class="section-header">
        <span class="fire">💬</span>
        <h2>ULASAN PEMBELI</h2>
        <p class="section-subtitle">
          Rating rata-rata <strong style="color:#f59e0b"><?= $avg_rating; ?> ⭐</strong>
          dari <strong><?= number_format($total_reviews); ?></strong> pembeli
        </p>
      </div>

      <style>
      .reviews-section { padding: 0 1.2rem 2.5rem; max-width: 1100px; margin: 0 auto; }
      .reviews-track-wrap { overflow: hidden; position: relative; }
      .reviews-track {
          display: flex;
          gap: 16px;
          animation: scrollReviews 30s linear infinite;
          width: max-content;
      }
      .reviews-track:hover { animation-play-state: paused; }
      @keyframes scrollReviews {
          0%   { transform: translateX(0); }
          100% { transform: translateX(-50%); }
      }
      .review-item {
          background: linear-gradient(135deg, #0f172a, #1e293b);
          border: 1px solid rgba(255,255,255,0.07);
          border-radius: 16px;
          padding: 18px 20px;
          min-width: 260px;
          max-width: 280px;
          flex-shrink: 0;
      }
      .review-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
      .review-avatar {
          width: 36px; height: 36px; border-radius: 50%;
          background: linear-gradient(135deg, #3b82f6, #6366f1);
          display: flex; align-items: center; justify-content: center;
          font-weight: 800; font-size: 0.9rem; color: #fff; flex-shrink: 0;
      }
      .review-user { font-size: 0.85rem; font-weight: 600; color: #e2e8f0; }
      .review-game { font-size: 0.72rem; color: #64748b; }
      .review-stars { color: #f59e0b; font-size: 0.95rem; margin-bottom: 8px; letter-spacing: 1px; }
      .review-text { font-size: 0.82rem; color: #94a3b8; line-height: 1.5; }
      .review-date { font-size: 0.7rem; color: #334155; margin-top: 10px; }
      </style>

      <div class="reviews-track-wrap">
        <div class="reviews-track" id="reviewsTrack">
          <?php
          // Duplikasi array untuk efek infinite scroll
          $all = array_merge($reviews, $reviews);
          foreach ($all as $rv):
              $stars = str_repeat('★', $rv['rating']) . str_repeat('☆', 5 - $rv['rating']);
              $avatar_letter = strtoupper(substr($rv['username'], 0, 1));
              $komentar = $rv['komentar'] ?: 'Transaksi lancar dan cepat!';
              $tanggal  = date('d M Y', strtotime($rv['created_at']));
              $username_display = substr($rv['username'], 0, 3) . str_repeat('*', max(0, strlen($rv['username']) - 3));
          ?>
          <div class="review-item">
            <div class="review-header">
              <div class="review-avatar"><?= $avatar_letter; ?></div>
              <div>
                <div class="review-user"><?= htmlspecialchars($username_display); ?></div>
                <div class="review-game"><?= htmlspecialchars($rv['game']); ?></div>
              </div>
            </div>
            <div class="review-stars"><?= $stars; ?></div>
            <div class="review-text">"<?= htmlspecialchars($komentar); ?>"</div>
            <div class="review-date"><?= $tanggal; ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

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
