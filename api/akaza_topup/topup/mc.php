<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth_helper.php';
$logged_in_user = auth_check();
$game_id = $_GET['id'] ?? null;
$game_name = "Magic Chess: Go Go"; // Default
$game_img = "../asset/img/mcgogo.jpeg"; // Default fallback



// Jika id tidak dikirim di URL, cari id secara otomatis dari database berdasarkan nama game
if (!$game_id) {
    $q_find = mysqli_query($conn, "SELECT id, name, thumbnail FROM games WHERE name LIKE '%Magic Chess%' OR slug = 'mc' LIMIT 1");
    if ($q_find && $r_find = mysqli_fetch_assoc($q_find)) {
        $game_id = $r_find['id'];
        $game_name = $r_find['name'];
        if ($r_find['thumbnail']) {
            $game_img = $r_find['thumbnail'];
        }
    }
}

if ($game_id) {
    require_once __DIR__ . '/../helper.php';
    $details = get_game_details($game_id, $game_name, $game_img);
    $game_name = $details['name'];
    $game_img = $details['image'];
}

// Ambil rata-rata rating dari DB
$game_name_esc = mysqli_real_escape_string($conn, $game_name);
$rev_query = mysqli_query($conn, "SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM reviews WHERE game = '$game_name_esc'");
$rev_row = mysqli_fetch_assoc($rev_query);
$total_rating = (int)($rev_row['total'] ?? 0);
$avg_rating = $total_rating > 0 ? round((float)$rev_row['avg_rating'], 1) : 0;
$stars_full = floor($avg_rating);
$stars_display = str_repeat('★', $stars_full) . str_repeat('☆', 5 - $stars_full);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Top-up <?= $game_name; ?> — Akaza Store</title>
  <script>
    (function() {
      const saved = localStorage.getItem('akaza_theme') || 'dark';
      document.documentElement.setAttribute('data-theme', saved);
      if (saved === 'dark') document.documentElement.classList.add('dark');
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="mc.css">
  <style>
  /* Navigation Bar Styles */
  .navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    padding: 12px 24px;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    background: rgba(15, 23, 42, 0.6);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.3s ease;
  }
  .nav-inner {
    max-width: 1260px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }
  .brand {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .back-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.05);
    color: #fff;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s, color 0.2s;
  }
  .back-btn:hover {
    background: #ffc400;
    color: #111;
    transform: translateX(-3px);
  }
  .back-icon {
    width: 18px;
    height: 18px;
  }
  .brand-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: #ffc400;
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }
  .brand-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
  }
  .brand-text {
    display: flex;
    flex-direction: column;
  }
  .brand-name {
    font-weight: 800;
    font-size: 15px;
    color: #fff;
    letter-spacing: 0.5px;
    line-height: 1.2;
  }
  .brand-sub {
    font-size: 11px;
    color: #9e9aa3;
  }
  .nav-right {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .nav-home-link {
    color: #9e9aa3;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 8px;
    transition: color 0.2s, background 0.2s;
  }
  .nav-home-link:hover {
    color: #ffc400;
    background: rgba(255, 255, 255, 0.03);
  }
  .theme-toggle {
    background: none;
    border: none;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    color: #9e9aa3;
    transition: background 0.2s, color 0.2s;
    font-size: 18px;
    position: relative;
  }
  .theme-toggle:hover {
    background: rgba(255, 255, 255, 0.04);
    color: #ffc400;
  }
  .theme-toggle .icon-sun,
  .theme-toggle .icon-moon {
    position: absolute;
    transition: opacity 0.3s ease, transform 0.3s ease;
  }
  .theme-toggle .icon-sun {
    opacity: 1;
    transform: rotate(0deg);
  }
  .theme-toggle .icon-moon {
    opacity: 0;
    transform: rotate(-90deg);
  }
  [data-theme="light"] .theme-toggle .icon-sun {
    opacity: 0;
    transform: rotate(90deg);
  }
  [data-theme="light"] .theme-toggle .icon-moon {
    opacity: 1;
    transform: rotate(0deg);
  }
  
  /* Light theme variables custom overrides for nav */
  [data-theme="light"] .navbar {
    background: rgba(255, 255, 255, 0.8);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  }
  [data-theme="light"] .brand-name {
    color: #111;
  }
  [data-theme="light"] .back-btn {
    background: rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.05);
    color: #111;
  }
  [data-theme="light"] .back-btn:hover {
    background: #e5a800;
    color: #fff;
  }

  /* Hamburger Menu Button */
  .hamburger {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    width: 36px;
    height: 36px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px;
    padding: 6px;
    border-radius: 10px;
    transition: background 0.2s;
    flex-shrink: 0;
  }
  .hamburger span {
    display: block;
    width: 22px;
    height: 2px;
    background: #fff;
    border-radius: 2px;
    transition: transform 0.3s, opacity 0.3s;
  }
  [data-theme="light"] .hamburger span {
    background: #111;
  }
  .hamburger:hover {
    background: rgba(255, 255, 255, 0.05);
  }
  [data-theme="light"] .hamburger:hover {
    background: rgba(0, 0, 0, 0.05);
  }
  
  /* Mobile Navigation Drawer */
  .mobile-nav {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  .mobile-nav.open {
    display: block;
    pointer-events: auto;
    opacity: 1;
  }
  .mobile-nav-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
  }
  .mobile-nav-drawer {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 280px;
    max-width: 80vw;
    background: #1e1e22;
    box-shadow: -8px 0 40px rgba(0, 0, 0, 0.5);
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  [data-theme="light"] .mobile-nav-drawer {
    background: #ffffff;
    box-shadow: -8px 0 40px rgba(0, 0, 0, 0.1);
  }
  .mobile-nav.open .mobile-nav-drawer {
    transform: translateX(0);
  }
  .mobile-nav-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  }
  [data-theme="light"] .mobile-nav-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  }
  .mobile-nav-header .brand-name {
    font-size: 18px;
    font-weight: 800;
    color: #ffc400;
  }
  .mobile-nav-close {
    background: none;
    border: none;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    color: #9e9aa3;
    font-size: 20px;
    transition: background 0.2s, color 0.2s;
  }
  .mobile-nav-close:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #ffc400;
  }
  [data-theme="light"] .mobile-nav-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #e5a800;
  }
  .mobile-nav-links {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .mobile-nav-links a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 10px;
    color: #e0dfe4;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
  }
  [data-theme="light"] .mobile-nav-links a {
    color: #333;
  }
  .mobile-nav-links a:hover {
    background: rgba(255, 255, 255, 0.04);
    color: #ffc400;
  }
  [data-theme="light"] .mobile-nav-links a:hover {
    background: rgba(0, 0, 0, 0.03);
    color: #e5a800;
  }
  .mobile-nav-links .auth-section {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
  }
  [data-theme="light"] .mobile-nav-links .auth-section {
    border-top: 1px solid rgba(0, 0, 0, 0.06);
  }
  .mobile-nav-links .auth-label {
    color: #7a7780;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 8px 14px;
  }
  [data-theme="light"] .mobile-nav-links .auth-label {
    color: #888;
  }

  @media (max-width: 768px) {
    .nav-home-link {
      display: none !important;
    }
    .hamburger {
      display: flex !important;
    }
  }
  </style>
</head>

<body>

  <!-- Navigation Header with Back Button -->
  <header class="navbar">
    <div class="nav-inner">
      <div class="brand">
        <a href="../index.php" class="back-btn" aria-label="Kembali ke Halaman Utama">
          <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
        </a>
        <div class="brand-icon">
          <img src="../asset/img/akazachibi.png" alt="Logo">
        </div>
        <div class="brand-text">
          <div class="brand-name">AKAZASTORE</div>
          <div class="brand-sub">Top-up & Services</div>
        </div>
      </div>
      
      <div class="nav-right">
        <a href="../index.php" class="nav-home-link">🎮 List Game</a>
        <a href="../riwayat.php" class="nav-home-link">📋 Transaksi</a>
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle tema">
          <span class="icon-sun">☀️</span>
          <span class="icon-moon">🌙</span>
        </button>
        <button class="hamburger" id="hamburgerBtn" aria-label="Buka menu navigasi">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Navigation Drawer (Hamburger Menu) -->
  <div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    <div class="mobile-nav-drawer">
      <div class="mobile-nav-header">
        <span class="brand-name">AKAZASTORE</span>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Tutup menu">✕</button>
      </div>
      <div class="mobile-nav-links">
        <a href="../index.php">🎮 Topup / List Game</a>
        <a href="../riwayat.php">📋 Cek Transaksi</a>
        <a href="../kalkulator.php">🧮 Kalkulator</a>
        <div class="auth-section">
          <div class="auth-label">Akun</div>
          <?php if($logged_in_user): ?>
              <a href="../dashboard_user.php">👤 Akun: <?= $logged_in_user; ?></a>
              <a href="../auth/logout.php">🚪 Logout</a>
          <?php else: ?>
              <a href="../auth/login.php">🔑 Masuk</a>
              <a href="../auth/registrasi.php">📝 Daftar</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

    <div class="hero"></div>
    <div class="card-hero-wrapper">
        <div class="card-game">
            <img src="<?= $game_img; ?>" class="game-img" alt="<?= $game_name; ?>" onerror="this.src='../asset/img/mcgogo.jpeg'">
            <div>
                <div class="title"><?= strtoupper($game_name); ?></div>
                <div class="subtitle">Moonton</div>
                <div class="badge-container">
                    <div class="badge">⚡ Proses Cepat</div>
                    <div class="badge">💬 Layanan 24/7</div>
                    <div class="badge">🔒 Pembayaran Aman</div>
                </div>
            </div>
        </div>
    </div>

    <form action="../proses_topup.php" method="POST" id="topupForm">
    <div class="container">
      <div class="content-layout">
        <div class="form-box">

            <div class="step-card">
              <div class="step-row-inputs">
                <div class="field-group">
                  <label class="field-label">ID <span class="info-icon" onclick="openInfoModal('User ID', 'Untuk menemukan User ID kamu, buka game Magic Chess: Go Go lalu klik ikon profil di pojok kiri atas. User ID tertera di bawah nama IGN kamu. Contoh format: 123456789')">ⓘ</span></label>
                  <input type="number" name="user_id" id="userid" placeholder="Masukkan ID" required class="field-input" />
                </div>
                <div class="field-group">
                  <label class="field-label">Zone ID</label>
                  <input type="number" name="server" id="server" placeholder="Zone ID" required class="field-input" />
                </div>
              </div>
              
              <div class="step-row-inputs" style="margin-top: 15px;">
                <div class="field-group">
                  <label class="field-label">Nomor WhatsApp <span class="info-icon" onclick="openInfoModal('Nomor WhatsApp', 'Masukkan nomor WhatsApp aktif kamu untuk menerima bukti pembayaran dan konfirmasi pesanan. Pastikan nomor dalam format yang benar, contoh: 08123456789')">ⓘ</span></label>
                  <input type="tel" name="whatsapp" id="whatsapp" placeholder="08123xxx" required class="field-input" />
                </div>
                <div class="field-group">
                  <label class="field-label">Email</label>
                  <input type="email" name="email" id="email" placeholder="example@mail.com" required class="field-input" />
                </div>
              </div>
              
              <div id="usernameResult" class="username-result" style="display: none;">
                <span class="user-check-icon">🔍</span>
                <span class="user-check-text" id="usernameText">Memeriksa username...</span>
              </div>
            </div>

            <div class="step-card">
                <div class="step-title-row">
                  <span class="step-num">2</span>
                  <h2 class="step-heading">Pilih Nominal</h2>
                </div>

                <input type="hidden" name="game" value="Magic Chess: Go Go">
                <input type="hidden" name="item" id="itemInput">
                <input type="hidden" name="nominal" id="nominalInput">
                <input type="hidden" name="metode" id="metodeInput">

                <div class="category-section">
                    <h3 class="category-title">Special Items ✨</h3>
                    <div class="item-grid" id="specialItems"></div>
                </div>

                <div class="category-section">
                    <h3 class="category-title">Diamonds 💎</h3>
                    <div class="item-grid" id="diamondList"></div>
                </div>
            </div>

            <div class="step-card">
                <div class="step-title-row">
                  <span class="step-num">3</span>
                  <h2 class="step-heading">Pilih Pembayaran</h2>
                </div>

                <div class="payment-methods">
                    <div class="payment-option" data-method="qris">
                        <span class="pm-icon">📱</span>
                        <span class="pm-name">QRIS</span>
                    </div>
                    <div class="payment-option" data-method="bank">
                        <span class="pm-icon">🏦</span>
                        <span class="pm-name">Virtual Account</span>
                    </div>
                </div>
                
                <select id="payment" class="payment-select" style="display:none;">
                    <option value="">-- Pilih Metode --</option>
                    <option value="qris">QRIS</option>
                    <option value="bank">Virtual Account</option>
                </select>
            </div>

        </div>
        <aside class="sidebar">
          <div class="sidebar-card rating-card">
            <h4 class="sidebar-title">Ulasan dan rating</h4>
            <div class="rating-display">
              <span class="rating-number"><?= $avg_rating ?></span>
              <div class="rating-stars"><?= $stars_display ?></div>
            </div>
            <p class="rating-sub">Berdasarkan total <?= $total_rating ?> rating</p>
          </div>

          <div class="sidebar-card help-card">
            <div class="help-inner">
              <span class="help-icon">💬</span>
              <div>
                <strong>Butuh Bantuan?</strong>
                <p>Kamu bisa hubungi admin disini.</p>
              </div>
            </div>
          </div>

          <div class="sidebar-card order-card">
            <div class="order-empty" id="orderEmpty">
              <p>Belum ada item produk yang dipilih.</p>
            </div>
            <div class="order-detail" id="orderDetail" style="display:none;">
              <div class="order-row">
                <span class="order-label">Produk</span>
                <span class="order-value" id="orderProduct">-</span>
              </div>
              <div class="order-row">
                <span class="order-label">Harga</span>
                <span class="order-value order-price" id="orderPrice">Rp 0</span>
              </div>
            </div>
            <button type="button" class="order-btn" id="btnTriggerModal">
              💬 Pesan Sekarang!
            </button>
          </div>
        </aside>

      </div>
      <div class="modal-overlay" id="confirmModal">
        <div class="modal-box">
          <div class="modal-header">
            <div class="check-circle">✔</div>
            <h3>Buat Pesanan</h3>
            <p>Pastikan data akun Kamu dan produk yang Kamu pilih valid dan sesuai.</p>
          </div>
          
          <div class="modal-body">
            <div class="detail-row">
              <span class="d-label">Username</span>
              <span class="d-val" id="m_username">-</span>
            </div>
            <div class="detail-row">
              <span class="d-label">ID</span>
              <span class="d-val" id="m_id">-</span>
            </div>
            <div class="detail-row">
              <span class="d-label">Server</span>
              <span class="d-val" id="m_server">-</span>
            </div>
            <div class="detail-row">
              <span class="d-label">Item</span>
              <span class="d-val" id="m_item">-</span>
            </div>
            <div class="detail-row">
              <span class="d-label">Product</span>
              <span class="d-val" id="m_product">Magic Chess: Go Go</span>
            </div>
            <div class="detail-row">
              <span class="d-label">Payment</span>
              <span class="d-val" id="m_payment">-</span>
            </div>
          </div>

          <div class="modal-terms">
            <label class="terms-label">
              <input type="checkbox" id="termsCheck">
              <span class="checkmark"></span>
              <span class="terms-text">Dengan mengklik <span class="highlight">Pesan Sekarang</span>, kamu sudah menyetujui <span class="highlight">Syarat & Ketentuan</span> yang berlaku</span>
            </label>
          </div>

          <div class="modal-actions">
            <button type="submit" class="btn-primary" id="btnSubmitFinal" disabled>Pesan Sekarang!</button>
            <button type="button" class="btn-secondary" id="btnCancelModal">Batalkan</button>
          </div>
        </div>
      </div>

    </form>
</div>

<div class="mobile-sticky-order" id="mobileOrderBar">
  <div class="mso-info">
    <div class="mso-title" id="msoItem">-</div>
    <div class="mso-price" id="msoPrice">Rp 0</div>
  </div>
  <button type="button" class="order-btn" id="msoBtnTrigger">Pesan Sekarang</button>
</div>

<!-- Info Modal -->
<div class="info-modal-overlay" id="infoModal">
  <div class="info-modal-box">
    <div class="info-modal-header">
      <div class="info-modal-icon">ⓘ</div>
      <h3 id="infoModalTitle">Informasi</h3>
      <button class="info-modal-close" id="infoModalClose" aria-label="Tutup">✕</button>
    </div>
    <div class="info-modal-body">
      <p id="infoModalText"></p>
    </div>
    <div class="info-modal-footer">
      <button class="info-modal-btn" id="infoModalOk">Mengerti</button>
    </div>
  </div>
</div>

<script>
function openInfoModal(title, text) {
  document.getElementById('infoModalTitle').textContent = title;
  document.getElementById('infoModalText').textContent = text;
  document.getElementById('infoModal').classList.add('active');
}
function closeInfoModal() {
  document.getElementById('infoModal').classList.remove('active');
}
document.getElementById('infoModalClose').addEventListener('click', closeInfoModal);
document.getElementById('infoModalOk').addEventListener('click', closeInfoModal);
document.getElementById('infoModal').addEventListener('click', function(e) {
  if (e.target === this) closeInfoModal();
});

// Theme Toggle Script
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    if (next === 'dark') document.documentElement.classList.add('dark');
    else document.documentElement.classList.remove('dark');
    localStorage.setItem('akaza_theme', next);
  });
}

// Hamburger Navigation Drawer Script
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileNav = document.getElementById('mobileNav');
const mobileNavClose = document.getElementById('mobileNavClose');
const mobileNavOverlay = document.getElementById('mobileNavOverlay');

if (hamburgerBtn && mobileNav) {
  hamburgerBtn.addEventListener('click', () => {
    mobileNav.classList.add('open');
  });
}
if (mobileNavClose) {
  mobileNavClose.addEventListener('click', () => {
    mobileNav.classList.remove('open');
  });
}
if (mobileNavOverlay) {
  mobileNavOverlay.addEventListener('click', () => {
    mobileNav.classList.remove('open');
  });
}
</script>

</body>
<script>
  window.gameId = '<?= $game_id ?>';
  window.apiBase = '<?= get_api_base_url() ?>';
</script>
<script src="mc.js"></script>
</html>