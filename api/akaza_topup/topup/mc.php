<?php
$game_id = $_GET['id'] ?? null;
$game_name = "Magic Chess: Go Go"; // Default
$game_img = "../asset/img/mcgogo.jpeg"; // Default fallback

if ($game_id) {
    require_once __DIR__ . '/../helper.php';
    $details = get_game_details($game_id, $game_name, $game_img);
    $game_name = $details['name'];
    $game_img = $details['image'];
}
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
</head>

<body>

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
                  <label class="field-label">ID <span class="info-icon" title="Masukkan User ID game kamu">ⓘ</span></label>
                  <input type="number" name="user_id" id="userid" placeholder="Masukkan ID" required class="field-input" />
                </div>
                <div class="field-group">
                  <label class="field-label">Zone ID</label>
                  <input type="number" name="server" id="server" placeholder="Zone ID" required class="field-input" />
                </div>
              </div>
              
              <div class="step-row-inputs" style="margin-top: 15px;">
                <div class="field-group">
                  <label class="field-label">Nomor WhatsApp <span class="info-icon" title="Untuk konfirmasi pesanan">ⓘ</span></label>
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
              <span class="rating-number">0</span>
              <div class="rating-stars">★★★★★</div>
            </div>
            <p class="rating-sub">Berdasarkan total 0 rating</p>
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

</body>
<script src="mc.js"></script>
</html>