<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/auth_helper.php';

$logged_in_user = auth_check();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalkulator Analisis Game — Akaza Store</title>
  <script>
    (function() {
      const saved = localStorage.getItem('akaza_theme') || 'dark';
      document.documentElement.setAttribute('data-theme', saved);
      if (saved === 'dark') document.documentElement.classList.add('dark');
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --bg: #0b0f19;
      --panel: #131926;
      --panel-border: rgba(255, 255, 255, 0.05);
      --text: #e2e8f0;
      --text-muted: #94a3b8;
      --accent: #ffc400;
      --accent-hover: #e5a800;
      --glass: rgba(255, 255, 255, 0.03);
      --glass-hover: rgba(255, 255, 255, 0.05);
      --input-bg: #0f1320;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    [data-theme="light"] {
      --bg: #f8fafc;
      --panel: #ffffff;
      --panel-border: rgba(0, 0, 0, 0.06);
      --text: #0f172a;
      --text-muted: #64748b;
      --accent: #e5a800;
      --accent-hover: #cc9600;
      --glass: rgba(0, 0, 0, 0.02);
      --glass-hover: rgba(0, 0, 0, 0.04);
      --input-bg: #f1f5f9;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      transition: background-color 0.3s, color 0.3s;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

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
    [data-theme="light"] .navbar {
      background: rgba(255, 255, 255, 0.8);
      border-bottom: 1px solid rgba(0, 0, 0, 0.06);
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
    [data-theme="light"] .back-btn {
      background: rgba(0, 0, 0, 0.03);
      border: 1px solid rgba(0, 0, 0, 0.05);
      color: #111;
    }
    .back-btn:hover {
      background: var(--accent);
      color: #111;
      transform: translateX(-3px);
    }
    [data-theme="light"] .back-btn:hover {
      color: #fff;
    }
    .back-icon {
      width: 18px;
      height: 18px;
    }
    .brand-icon {
      width: 38px;
      height: 38px;
      border-radius: 8px;
      background: var(--accent);
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
    [data-theme="light"] .brand-name {
      color: #111;
    }
    .brand-sub {
      font-size: 11px;
      color: var(--text-muted);
    }
    .nav-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .nav-home-link {
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 600;
      padding: 8px 12px;
      border-radius: 8px;
      transition: color 0.2s, background 0.2s;
      text-decoration: none;
    }
    .nav-home-link:hover {
      color: var(--accent);
      background: var(--glass);
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
      color: var(--text-muted);
      transition: background 0.2s, color 0.2s;
      font-size: 18px;
      position: relative;
    }
    .theme-toggle:hover {
      background: var(--glass);
      color: var(--accent);
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
      background: var(--glass);
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
      color: var(--accent);
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
      color: var(--text-muted);
      font-size: 20px;
      transition: background 0.2s, color 0.2s;
    }
    .mobile-nav-close:hover {
      background: var(--glass);
      color: var(--accent);
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
      color: var(--text);
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s, color 0.2s;
    }
    .mobile-nav-links a:hover {
      background: var(--glass);
      color: var(--accent);
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
      color: var(--text-muted);
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 8px 14px;
    }

    /* Hero Section */
    .hero {
      padding: 50px 24px 30px;
      text-align: center;
      max-width: 800px;
      margin: 0 auto;
    }
    .hero-title {
      font-size: 36px;
      font-weight: 900;
      letter-spacing: -1px;
      background: linear-gradient(135deg, #fff 30%, var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 12px;
    }
    [data-theme="light"] .hero-title {
      background: linear-gradient(135deg, #111 50%, var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
      color: var(--text-muted);
      font-size: 15px;
      line-height: 1.6;
    }

    /* Container & Layout */
    .container {
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
      padding: 0 24px 80px;
      flex-grow: 1;
    }

    /* Tabs Interface */
    .tabs-nav {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-bottom: 30px;
      border-bottom: 1px solid var(--panel-border);
      padding-bottom: 16px;
      overflow-x: auto;
    }
    .tab-btn {
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 700;
      padding: 10px 20px;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .tab-btn:hover {
      background: var(--glass);
      color: var(--text);
    }
    .tab-btn.active {
      background: rgba(255, 196, 0, 0.1);
      color: var(--accent);
      border: 1px solid rgba(255, 196, 0, 0.2);
    }
    [data-theme="light"] .tab-btn.active {
      background: rgba(229, 168, 0, 0.08);
    }

    /* Tab Panel */
    .tab-panel {
      display: none;
      animation: fadeIn 0.4s ease forwards;
    }
    .tab-panel.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Card Layout */
    .calc-card {
      background: var(--panel);
      border: 1px solid var(--panel-border);
      border-radius: 20px;
      box-shadow: var(--shadow);
      padding: 32px;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 32px;
    }
    @media (max-width: 820px) {
      .calc-card {
        grid-template-columns: 1fr;
      }
    }

    /* Form Styles */
    .calc-form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .form-label {
      font-size: 13px;
      font-weight: 700;
      color: var(--text);
    }
    .form-input {
      background: var(--input-bg);
      border: 1px solid var(--panel-border);
      border-radius: 12px;
      padding: 14px 16px;
      color: var(--text);
      font-size: 15px;
      font-weight: 600;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      width: 100%;
    }
    .form-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.15);
    }
    .submit-btn {
      background: var(--accent);
      color: #111;
      border: none;
      border-radius: 12px;
      padding: 16px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      transition: transform 0.2s, background-color 0.2s, box-shadow 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 10px;
    }
    [data-theme="light"] .submit-btn {
      color: #fff;
    }
    .submit-btn:hover {
      background: var(--accent-hover);
      box-shadow: 0 4px 15px rgba(255, 196, 0, 0.3);
      transform: translateY(-2px);
    }
    .submit-btn:active {
      transform: translateY(0);
    }

    /* Result Panel */
    .result-section {
      background: var(--input-bg);
      border: 1px solid var(--panel-border);
      border-radius: 16px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      min-height: 250px;
      text-align: center;
    }
    .result-placeholder {
      color: var(--text-muted);
      font-size: 14px;
      line-height: 1.6;
    }
    .result-placeholder-icon {
      font-size: 40px;
      margin-bottom: 12px;
      display: block;
    }

    /* Real Results output */
    .result-output {
      display: none;
      animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .result-output.active {
      display: block;
    }
    @keyframes scaleIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .result-val {
      font-size: 54px;
      font-weight: 900;
      color: var(--accent);
      line-height: 1;
      margin-bottom: 8px;
    }
    .result-label {
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 16px;
    }
    .result-desc {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.6;
      border-top: 1px solid var(--panel-border);
      padding-top: 16px;
      margin-top: 16px;
    }
    .highlight-win {
      color: #10b981;
      font-weight: 800;
    }

    /* Magic / Zodiac specific displays */
    .detail-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-top: 16px;
    }
    .detail-item {
      background: var(--panel);
      border: 1px solid var(--panel-border);
      border-radius: 8px;
      padding: 12px;
      text-align: left;
    }
    .detail-item-title {
      font-size: 10px;
      text-transform: uppercase;
      color: var(--text-muted);
      font-weight: 700;
      margin-bottom: 4px;
    }
    .detail-item-val {
      font-size: 14px;
      font-weight: 800;
      color: var(--text);
    }

    @media (max-width: 768px) {
      .nav-home-link {
        display: none !important;
      }
      .hamburger {
        display: flex !important;
      }
      .hero-title {
        font-size: 28px;
      }
    }
  </style>
</head>

<body>

  <!-- Navigation Header -->
  <header class="navbar">
    <div class="nav-inner">
      <div class="brand">
        <a href="index.php" class="back-btn" aria-label="Kembali ke Halaman Utama">
          <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
        </a>
        <div class="brand-icon">
          <img src="asset/img/akazachibi.png" alt="Logo">
        </div>
        <div class="brand-text">
          <div class="brand-name">AKAZASTORE</div>
          <div class="brand-sub">Tools & Calculators</div>
        </div>
      </div>
      
      <div class="nav-right">
        <a href="index.php" class="nav-home-link">🎮 List Game</a>
        <a href="riwayat.php" class="nav-home-link">📋 Transaksi</a>
        <?php if($logged_in_user): ?>
            <a class="nav-home-link auth" href="dashboard_user.php" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 4px 12px; font-weight: 600;">👤 Akun: <?= $logged_in_user; ?></a>
            <a class="nav-home-link auth" href="auth/logout.php" style="font-weight: 600;">Logout</a>
        <?php else: ?>
            <a class="nav-home-link auth" href="auth/login.php" style="font-weight: 600;">Masuk</a>
            <a class="nav-home-link auth" href="auth/registrasi.php" style="font-weight: 600;">Daftar</a>
        <?php endif; ?>
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
        <a href="index.php">🎮 Topup / List Game</a>
        <a href="riwayat.php">📋 Cek Transaksi</a>
        <a href="kalkulator.php">🧮 Kalkulator</a>
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

  <!-- Hero Title -->
  <section class="hero">
    <h1 class="hero-title">Kalkulator Analisis Game</h1>
    <p class="hero-subtitle">Bantu rencanakan strategi push winrate dan target draw skin Anda secara akurat dan gratis hanya di AkazaStore.</p>
  </section>

  <!-- Main Calculator Container -->
  <main class="container">
    
    <!-- Tab navigation -->
    <div class="tabs-nav">
      <button class="tab-btn active" data-tab="winrate">
        <span>📈</span> Kalkulator Win Rate
      </button>
      <button class="tab-btn" data-tab="magicwheel">
        <span>🎡</span> Magic Wheel
      </button>
      <button class="tab-btn" data-tab="zodiac">
        <span>🌟</span> Zodiac Skin
      </button>
    </div>

    <!-- TAB 1: WINRATE CALCULATOR -->
    <div class="tab-panel active" id="panel-winrate">
      <div class="calc-card">
        <div class="calc-form">
          <div class="form-group">
            <label class="form-label" for="wr-matches">Total Match Saat Ini</label>
            <input type="number" class="form-input" id="wr-matches" placeholder="Contoh: 850" min="1">
          </div>
          <div class="form-group">
            <label class="form-label" for="wr-current">Win Rate Saat Ini (%)</label>
            <input type="number" step="0.01" class="form-input" id="wr-current" placeholder="Contoh: 54.2" min="0" max="100">
          </div>
          <div class="form-group">
            <label class="form-label" for="wr-target">Target Win Rate (%)</label>
            <input type="number" step="0.01" class="form-input" id="wr-target" placeholder="Contoh: 60" min="0" max="100">
          </div>
          <button class="submit-btn" onclick="calculateWinrate()">
            <span>⚡</span> Hitung Win Rate
          </button>
        </div>

        <div class="result-section" id="result-winrate-box">
          <div class="result-placeholder" id="placeholder-winrate">
            <span class="result-placeholder-icon">📈</span>
            Masukkan total match, winrate saat ini, dan target winrate impian Anda untuk menghitung jumlah winstreak yang dibutuhkan.
          </div>
          <div class="result-output" id="output-winrate">
            <div class="result-val" id="wr-result-val">0</div>
            <div class="result-label">Kemenangan Beruntun (Winstreak)</div>
            <div class="result-desc" id="wr-result-desc">
              Untuk mencapai target winrate <span class="highlight-win" id="wr-target-show">0%</span>, Anda harus menang berturut-turut sebanyak <span class="highlight-win" id="wr-wins-show">0</span> kali tanpa ada kekalahan sekali pun. Total match Anda akan bertambah menjadi <span class="highlight-win" id="wr-total-show">0</span> match.
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 2: MAGIC WHEEL CALCULATOR -->
    <div class="tab-panel" id="panel-magicwheel">
      <div class="calc-card">
        <div class="calc-form">
          <div class="form-group">
            <label class="form-label" for="mw-points">Magic Point Saat Ini</label>
            <input type="number" class="form-input" id="mw-points" placeholder="Masukkan 0 - 199" min="0" max="199">
          </div>
          <div class="form-group">
            <label class="form-label" for="mw-potions">Jumlah Magic Potion yang Dimiliki</label>
            <input type="number" class="form-input" id="mw-potions" placeholder="Contoh: 5 (opsional)" min="0" value="0">
          </div>
          <button class="submit-btn" onclick="calculateMagicWheel()">
            <span>⚡</span> Hitung Biaya Magic Wheel
          </button>
        </div>

        <div class="result-section" id="result-magic-box">
          <div class="result-placeholder" id="placeholder-magic">
            <span class="result-placeholder-icon">🎡</span>
            Masukkan poin Magic Wheel Anda saat ini untuk memperkirakan sisa diamond serta jumlah magic potion yang diperlukan untuk meraih skin Legend impian.
          </div>
          <div class="result-output" id="output-magic">
            <div class="result-val" id="mw-result-val">0</div>
            <div class="result-label">Sisa Spin Dibutuhkan</div>
            <div class="result-desc">
              Anda memerlukan sisa spin sebanyak <span class="highlight-win" id="mw-spins-show">0</span> kali untuk meraih skin Legend (200 Poin).
              
              <div class="detail-grid">
                <div class="detail-item">
                  <div class="detail-item-title">Metode Spin Normal</div>
                  <div class="detail-item-val" id="mw-norm-dm">0 💎</div>
                </div>
                <div class="detail-item">
                  <div class="detail-item-title">Metode Spin Paket 5x</div>
                  <div class="detail-item-val" id="mw-pack-dm">0 💎</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 3: ZODIAC CALCULATOR -->
    <div class="tab-panel" id="panel-zodiac">
      <div class="calc-card">
        <div class="calc-form">
          <div class="form-group">
            <label class="form-label" for="z-star">Star Power Saat Ini</label>
            <input type="number" class="form-input" id="z-star" placeholder="Masukkan 0 - 99" min="0" max="99">
          </div>
          <button class="submit-btn" onclick="calculateZodiac()">
            <span>⚡</span> Hitung Biaya Zodiac
          </button>
        </div>

        <div class="result-section" id="result-zodiac-box">
          <div class="result-placeholder" id="placeholder-zodiac">
            <span class="result-placeholder-icon">🌟</span>
            Masukkan total Star Power Zodiac Anda saat ini untuk memperkirakan jumlah Aurora/Diamond yang dibutuhkan untuk mengklaim skin Zodiac.
          </div>
          <div class="result-output" id="output-zodiac">
            <div class="result-val" id="z-result-val">0</div>
            <div class="result-label">Sisa Poin Star Power</div>
            <div class="result-desc">
              Anda memerlukan sisa Star Power sebanyak <span class="highlight-win" id="z-needed-show">0</span> poin untuk mengklaim skin Zodiac.
              
              <div class="detail-grid">
                <div class="detail-item">
                  <div class="detail-item-title">Estimasi Sisa Draw</div>
                  <div class="detail-item-val" id="z-draws-show">0 Draw</div>
                </div>
                <div class="detail-item">
                  <div class="detail-item-title">Estimasi Aurora/Diamond</div>
                  <div class="detail-item-val" id="z-diamonds-show">0 COA / 💎</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <script>
    // Tab Toggling
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetTab = btn.getAttribute('data-tab');

        tabButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        tabPanels.forEach(panel => {
          panel.classList.remove('active');
          if (panel.id === `panel-${targetTab}`) {
            panel.classList.add('active');
          }
        });
      });
    });

    // Winrate Calculator Logic
    function calculateWinrate() {
      const matchesInput = document.getElementById('wr-matches').value;
      const currentWRInput = document.getElementById('wr-current').value;
      const targetWRInput = document.getElementById('wr-target').value;

      if (!matchesInput || !currentWRInput || !targetWRInput) {
        alert('Mohon masukkan semua data input.');
        return;
      }

      const totalMatches = parseInt(matchesInput);
      const currentWR = parseFloat(currentWRInput);
      const targetWR = parseFloat(targetWRInput);

      if (totalMatches <= 0 || currentWR < 0 || currentWR > 100 || targetWR < 0 || targetWR > 100) {
        alert('Mohon masukkan angka input yang valid.');
        return;
      }

      const placeholder = document.getElementById('placeholder-winrate');
      const output = document.getElementById('output-winrate');

      if (targetWR <= currentWR) {
        document.getElementById('wr-result-val').textContent = '0';
        document.getElementById('wr-result-desc').innerHTML = `Winrate Anda saat ini (<span class="highlight-win">${currentWR}%</span>) sudah memenuhi atau melebihi target (<span class="highlight-win">${targetWR}%</span>)!`;
        
        placeholder.style.display = 'none';
        output.classList.add('active');
        return;
      }

      if (targetWR >= 100) {
        document.getElementById('wr-result-val').textContent = '∞';
        document.getElementById('wr-result-desc').innerHTML = `Tidak mungkin mencapai target <span class="highlight-win">100% Winrate</span> jika Anda sudah pernah kalah.`;
        
        placeholder.style.display = 'none';
        output.classList.add('active');
        return;
      }

      // Formula: X = Math.ceil((T * (R - W)) / (1 - R))
      const T = totalMatches;
      const W = currentWR / 100;
      const R = targetWR / 100;

      const winsNeeded = Math.ceil((T * (R - W)) / (1 - R));
      const finalMatches = T + winsNeeded;

      document.getElementById('wr-result-val').textContent = winsNeeded;
      document.getElementById('wr-target-show').textContent = targetWR + '%';
      document.getElementById('wr-wins-show').textContent = winsNeeded;
      document.getElementById('wr-total-show').textContent = finalMatches;

      placeholder.style.display = 'none';
      output.classList.add('active');
    }

    // Magic Wheel Calculator Logic
    function calculateMagicWheel() {
      const mwPointsInput = document.getElementById('mw-points').value;
      const mwPotionsInput = document.getElementById('mw-potions').value || 0;

      if (mwPointsInput === '') {
        alert('Mohon masukkan Magic Point saat ini.');
        return;
      }

      const currentPoints = parseInt(mwPointsInput);
      const potions = parseInt(mwPotionsInput);

      if (currentPoints < 0 || currentPoints > 199 || potions < 0) {
        alert('Mohon masukkan angka input Magic Point yang valid (0 - 199).');
        return;
      }

      const placeholder = document.getElementById('placeholder-magic');
      const output = document.getElementById('output-magic');

      const targetPoints = 200;
      let spinsNeeded = targetPoints - currentPoints;
      
      // Magic potion reduces the number of spins needed
      spinsNeeded = Math.max(0, spinsNeeded - potions);

      const normalDiamonds = spinsNeeded * 60;
      
      // Pack method: 5 spins = 270 diamonds.
      const packagesOf5 = Math.floor(spinsNeeded / 5);
      const remainingSpins = spinsNeeded % 5;
      const packageDiamonds = (packagesOf5 * 270) + (remainingSpins * 60);

      document.getElementById('mw-result-val').textContent = spinsNeeded;
      document.getElementById('mw-spins-show').textContent = spinsNeeded;
      document.getElementById('mw-norm-dm').textContent = normalDiamonds.toLocaleString('id-ID') + ' 💎';
      document.getElementById('mw-pack-dm').textContent = packageDiamonds.toLocaleString('id-ID') + ' 💎';

      placeholder.style.display = 'none';
      output.classList.add('active');
    }

    // Zodiac Calculator Logic
    function calculateZodiac() {
      const zStarInput = document.getElementById('z-star').value;

      if (zStarInput === '') {
        alert('Mohon masukkan Star Power saat ini.');
        return;
      }

      const currentStar = parseInt(zStarInput);

      if (currentStar < 0 || currentStar > 99) {
        alert('Mohon masukkan Star Power Zodiac yang valid (0 - 99).');
        return;
      }

      const placeholder = document.getElementById('placeholder-zodiac');
      const output = document.getElementById('output-zodiac');

      const starNeeded = 100 - currentStar;
      
      // On average, players get around 1 to 5 star power per draw. Average is about 1.2 to 1.3 draws per star power point, which equates to about 85 draws total from 0 to 100.
      // Approximation formula for remaining draws:
      const estimatedDraws = Math.ceil(starNeeded * 0.85);
      const estimatedDiamonds = estimatedDraws * 20; // 1 draw costs 20 Aurora or Diamonds

      document.getElementById('z-result-val').textContent = starNeeded;
      document.getElementById('z-needed-show').textContent = starNeeded;
      document.getElementById('z-draws-show').textContent = estimatedDraws + ' Draw';
      document.getElementById('z-diamonds-show').textContent = estimatedDiamonds.toLocaleString('id-ID') + ' COA / 💎';

      placeholder.style.display = 'none';
      output.classList.add('active');
    }

    // Theme Toggle
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

    // Hamburger Nav Open/Close
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
</html>
