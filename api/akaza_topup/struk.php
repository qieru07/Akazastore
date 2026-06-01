<?php
include __DIR__ . "/koneksi.php";
require_once __DIR__ . "/helper.php";
require_once __DIR__ . "/midtrans_helper.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// ambil data dari DB
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

// Ambil detail metode pembayaran
$metode_code = $data['metode'];
$pay_query = mysqli_query($conn, "SELECT * FROM payment_methods WHERE code='$metode_code' LIMIT 1");
$pay_info = mysqli_fetch_assoc($pay_query);

// Dapatkan Snap Token secara dinamis jika status masih PENDING
$snap_token = null;
if ($data['status'] === 'PENDING') {
    $total_pembayaran = $data['nominal'] + $data['kode_unik'];
    $snap_token = dapatkan_snap_token($data['id'], $total_pembayaran, $data['item'], $data);
}

// Check if already reviewed
$already_reviewed = false;
$cek_review = mysqli_query($conn, "SELECT id FROM reviews WHERE transaksi_id = " . intval($data['id']));
if ($cek_review && mysqli_num_rows($cek_review) > 0) {
    $already_reviewed = true;
}

// format rupiah
function rupiah($angka){
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $data['id']; ?> — AkazaStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="struk.css">
    
    <style>
    /* ===== CONFETTI EFFECT ===== */
    .confetti-piece {
        position: fixed;
        width: 10px;
        height: 10px;
        top: -20px;
        z-index: 10001;
        border-radius: 50% 0 50% 50%;
        animation: fall 3.5s linear forwards;
        pointer-events: none;
    }
    @keyframes fall {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(105vh) rotate(720deg); opacity: 0; }
    }

    /* ===== CELEBRATORY POPUP MODAL ===== */
    .review-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(11, 15, 25, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 20px;
    }
    .review-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .review-modal-box {
        background: linear-gradient(135deg, #131926 0%, #1e2640 100%);
        border: 1px solid rgba(255, 196, 0, 0.25);
        box-shadow: 0 25px 60px rgba(0,0,0,0.65), 0 0 50px rgba(255, 196, 0, 0.08);
        border-radius: 28px;
        width: 460px;
        max-width: 100%;
        padding: 40px 32px;
        text-align: center;
        position: relative;
        transform: scale(0.85) translateY(40px);
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .review-modal-overlay.active .review-modal-box {
        transform: scale(1) translateY(0);
    }
    .modal-close-x {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: background 0.2s, color 0.2s;
    }
    .modal-close-x:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    .celebrate-icon {
        font-size: 4rem;
        line-height: 1;
        margin-bottom: 20px;
        display: inline-block;
        animation: pulseHeart 1.5s ease infinite alternate;
    }
    @keyframes pulseHeart {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }
    .modal-title {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #fff 40%, var(--accent, #ffc400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
    }
    .modal-subtitle {
        font-size: 0.9rem;
        color: #94a3b8;
        line-height: 1.5;
        margin-bottom: 24px;
        padding: 0 10px;
    }
    
    /* Interactive Stars */
    .modal-stars {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 24px;
    }
    .modal-stars span {
        font-size: 3rem;
        cursor: pointer;
        color: #2a334d;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        user-select: none;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    .modal-stars span:hover {
        transform: scale(1.25);
    }
    .modal-stars span.active {
        color: #ffc400;
        text-shadow: 0 0 20px rgba(255, 196, 0, 0.6);
        transform: scale(1.15);
    }
    .modal-textarea {
        width: 100%;
        background: #0f1320;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        color: #e2e8f0;
        padding: 16px;
        font-size: 0.92rem;
        font-family: inherit;
        resize: none;
        height: 110px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
        margin-bottom: 6px;
    }
    .modal-textarea:focus {
        outline: none;
        border-color: rgba(255, 196, 0, 0.4);
        box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.15);
    }
    .modal-char-count {
        font-size: 0.75rem;
        color: #4b5563;
        text-align: right;
        margin-bottom: 24px;
    }
    .modal-submit {
        width: 100%;
        background: linear-gradient(135deg, #ffc400, #ff9100);
        color: #111;
        border: none;
        border-radius: 16px;
        padding: 16px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        box-shadow: 0 4px 20px rgba(255, 145, 0, 0.35);
    }
    .modal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 145, 0, 0.45);
    }
    .modal-submit:active {
        transform: translateY(0);
    }
    .modal-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Success / Feedback States */
    .modal-success-screen {
        display: none;
        animation: scaleUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes scaleUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .modal-success-icon {
        font-size: 4.5rem;
        color: #10b981;
        margin-bottom: 20px;
        display: inline-block;
    }
    .modal-success-title {
        font-size: 1.4rem;
        color: #fff;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .modal-success-btn {
        margin-top: 24px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #fff;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modal-success-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    /* Inline receipt review widget */
    .receipt-review-widget {
        background: linear-gradient(135deg, #131926 0%, #171d30 100%);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 24px;
        margin-top: 20px;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
    .widget-stars {
        color: #ffc400;
        font-size: 1.5rem;
        margin-bottom: 8px;
        letter-spacing: 2px;
    }
    .widget-text {
        font-size: 0.88rem;
        color: #94a3b8;
        line-height: 1.5;
    }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="main-card">
        <!-- Header Branding -->
        <div class="header">
            <div class="brand">
                <?php if (isset($pay_info['type']) && $pay_info['type'] != 'bank'): ?>
                    <img src="asset/img/akazachibi.png" alt="Logo" class="brand-logo">
                <?php endif; ?>
                <div class="brand-name">AkazaStore</div>
            </div>
            <div class="status-chip <?= $data['status'] == 'Lunas' ? 'lunas' : 'pending'; ?>">
                <?= $data['status'] ?? 'PENDING'; ?>
            </div>
        </div>

        <!-- Order ID & Date -->
        <div class="order-meta">
            <span class="trx-id">Invoice #AKZ-<?= str_pad($data['id'], 5, '0', STR_PAD_LEFT); ?></span>
            <span class="trx-date"><?= date('d M Y, H:i', strtotime($data['tanggal'])); ?></span>
        </div>

        <div class="divider"></div>

        <!-- Customer Data -->
        <div class="data-grid">
            <div class="grid-item">
                <span class="label">Customer</span>
                <span class="value"><?= $data['username']; ?></span>
            </div>
            <div class="grid-item">
                <span class="label">User ID Game</span>
                <span class="value"><?= $data['user_id']; ?></span>
            </div>
            <div class="grid-item">
                <span class="label">Game</span>
                <span class="value"><?= $data['game']; ?></span>
            </div>
            <div class="grid-item">
                <span class="label">WhatsApp</span>
                <span class="value"><?= $data['whatsapp']; ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Billing Section -->
        <div class="billing-area">
            <div class="bill-item">
                <span class="bill-label"><?= $data['item']; ?></span>
                <span class="bill-value">Rp <?= rupiah($data['nominal']); ?></span>
            </div>
            <div class="bill-item tax">
                <span class="bill-label">Tax / Biaya Pelayanan</span>
                <span class="bill-value">+ <?= $data['kode_unik']; ?></span>
            </div>
            <div class="total-box">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-val">Rp <?= rupiah($data['nominal'] + $data['kode_unik']); ?></span>
            </div>
        </div>

        <!-- Payment Section (QRIS vs VA) -->
        <div class="payment-area">
            <?php if ($snap_token): ?>
                <div class="midtrans-pay-box" style="text-align: center; padding: 20px 0 10px;">
                    <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 16px;">Pembayaran via Midtrans otomatis &amp; aman.</p>
                    
                    <!-- Pilih Metode Pembayaran -->
                    <div id="payment-method-selector" style="display: flex; gap: 12px; margin-bottom: 18px; justify-content: center; flex-wrap: wrap;">
                        <div class="pay-method-card" id="method-qris" onclick="selectMethod('qris')" style="
                            flex: 1; min-width: 130px; max-width: 180px; padding: 14px 10px;
                            border: 2px solid #334155; border-radius: 14px; cursor: pointer;
                            background: #1e293b; transition: all 0.25s ease;
                            display: flex; flex-direction: column; align-items: center; gap: 8px;
                        ">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/200px-QRIS_logo.svg.png" alt="QRIS" style="width:56px;">
                            <span style="color: #cbd5e1; font-size: 0.82rem; font-weight: 600;">QRIS All Payment</span>
                        </div>
                        <div class="pay-method-card" id="method-bca" onclick="selectMethod('bca_va')" style="
                            flex: 1; min-width: 130px; max-width: 180px; padding: 14px 10px;
                            border: 2px solid #334155; border-radius: 14px; cursor: pointer;
                            background: #1e293b; transition: all 0.25s ease;
                            display: flex; flex-direction: column; align-items: center; gap: 8px;
                        ">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png" alt="BCA" style="width:56px;">
                            <span style="color: #cbd5e1; font-size: 0.82rem; font-weight: 600;">BCA Virtual Account</span>
                        </div>
                    </div>

                    <button id="pay-button" class="btn-primary" disabled style="background: linear-gradient(135deg, #10b981, #059669); color: white; width: 100%; padding: 15px; border-radius: 12px; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); font-size: 1rem; letter-spacing: 0.5px; transition: all 0.2s; max-width: 320px; margin: 0 auto; display: block; opacity: 0.45;">
                        💳 BAYAR SEKARANG
                    </button>
                    <p id="pay-hint" style="color:#64748b; font-size:0.78rem; margin-top:10px;">Pilih metode pembayaran di atas terlebih dahulu</p>
                </div>
            <?php else: ?>
                <?php if (isset($pay_info['type']) && $pay_info['type'] == 'qris'): ?>
                    <div class="qris-only">
                        <div class="qr-frame">
                            <?php if(!empty($pay_info['image'])): ?>
                                <img src="<?= get_image_base_url(); ?>/payments/<?= $pay_info['image']; ?>" alt="QRIS"
                                     onerror="this.onerror=null;this.src='https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=AKZ-<?= $data['id']; ?>';">
                            <?php else: ?>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=AKZ-<?= $data['id']; ?>" alt="QRIS">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="va-display">
                        <span class="va-title"><?= $pay_info['name'] ?? $data['metode']; ?></span>
                        <div class="va-card">
                            <span id="vaNumber"><?= $pay_info['account_number'] ?? 'BELUM DIATUR'; ?></span>
                            <button onclick="copyVA()" class="copy-btn">SALIN</button>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Bottom Warning -->
        <div class="warning-footer">
            Transfer tepat sesuai nominal agar diproses otomatis.
        </div>
    </div>

    <!-- Inline Review / Rating Section (Only shown if already reviewed) -->
    <?php if ($already_reviewed): ?>
        <?php
        $rev_query = mysqli_query($conn, "SELECT * FROM reviews WHERE transaksi_id = " . intval($data['id']) . " LIMIT 1");
        $rev_data = mysqli_fetch_assoc($rev_query);
        $stars_str = str_repeat('★', $rev_data['rating']) . str_repeat('☆', 5 - $rev_data['rating']);
        ?>
        <div class="receipt-review-widget">
            <div class="widget-stars"><?= $stars_str; ?></div>
            <div class="widget-text">"<?= htmlspecialchars($rev_data['komentar'] ?: 'Terima kasih atas ulasan Anda! Pembayaran sukses diproses.'); ?>"</div>
        </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="actions">
        <button onclick="window.print()" class="btn-primary">Print Invoice</button>
        <a href="index.php" class="btn-secondary">Beranda</a>
    </div>
</div>

<!-- CELEBRATORY REVIEW POPUP MODAL (Triggers automatically if status is Lunas and not reviewed yet) -->
<?php if ($data['status'] === 'Lunas' && !$already_reviewed): ?>
<div class="review-modal-overlay" id="reviewModal">
    <!-- Main Modal Form Box -->
    <div class="review-modal-box" id="modalFormContainer">
        <button class="modal-close-x" onclick="closeModal()">✕</button>
        
        <span class="celebrate-icon">🎉</span>
        <h3 class="modal-title">Pembayaran Sukses!</h3>
        <p class="modal-subtitle">Terima kasih telah berbelanja di AkazaStore! Bagaimana pengalaman top-up Anda kali ini?</p>

        <!-- Star Rating -->
        <div class="modal-stars" id="modalStars">
            <span data-val="1">★</span>
            <span data-val="2">★</span>
            <span data-val="3">★</span>
            <span data-val="4">★</span>
            <span data-val="5">★</span>
        </div>

        <!-- Comments -->
        <textarea class="modal-textarea" id="modalKomentar" placeholder="Tulis masukan/ulasan Anda di sini... (opsional)" maxlength="500"></textarea>
        <div class="modal-char-count"><span id="modalCharCount">0</span>/500</div>

        <!-- Submit Button -->
        <button class="modal-submit" id="modalSubmitBtn" onclick="submitModalReview()">
            🚀 KIRIM ULASAN SEKARANG
        </button>
    </div>

    <!-- Success Screen (Shown after submit) -->
    <div class="review-modal-box" id="modalSuccessContainer" style="display: none;">
        <span class="modal-success-icon">✅</span>
        <h3 class="modal-success-title">Ulasan Terkirim!</h3>
        <p class="modal-subtitle">Terima kasih banyak atas feedback Anda. Dukungan Anda sangat berarti bagi perkembangan AkazaStore!</p>
        <button class="modal-success-btn" onclick="closeModal()">Selesai</button>
    </div>
</div>
<?php endif; ?>

<script>
function copyVA() {
    const text = document.getElementById('vaNumber').innerText;
    navigator.clipboard.writeText(text);
    alert('Berhasil disalin!');
}

<?php if ($data['status'] === 'Lunas' && !$already_reviewed): ?>
document.addEventListener('DOMContentLoaded', () => {
    // Show Modal with clean delay
    setTimeout(() => {
        document.getElementById('reviewModal').classList.add('active');
        spawnConfetti();
    }, 800);
});

// Confetti Spawner
function spawnConfetti() {
    const colors = ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#ec4899', '#8b5cf6'];
    const container = document.body;
    for (let i = 0; i < 75; i++) {
        const piece = document.createElement('div');
        piece.classList.add('confetti-piece');
        piece.style.left = Math.random() * 100 + 'vw';
        piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        piece.style.width = Math.random() * 8 + 6 + 'px';
        piece.style.height = Math.random() * 8 + 6 + 'px';
        piece.style.animationDelay = Math.random() * 2.2 + 's';
        piece.style.animationDuration = Math.random() * 2 + 1.8 + 's';
        container.appendChild(piece);
        
        // Remove after animation completes
        setTimeout(() => piece.remove(), 4000);
    }
}

// Modal Rating Interactions
let modalSelectedRating = 0;
const modalStars = document.querySelectorAll('#modalStars span');

modalStars.forEach(star => {
    star.addEventListener('mouseenter', () => highlightModalStars(star.dataset.val));
    star.addEventListener('mouseleave', () => highlightModalStars(modalSelectedRating));
    star.addEventListener('click', () => {
        modalSelectedRating = parseInt(star.dataset.val);
        highlightModalStars(modalSelectedRating);
    });
});

function highlightModalStars(val) {
    modalStars.forEach(s => {
        s.classList.toggle('active', parseInt(s.dataset.val) <= parseInt(val));
    });
}

// Char counter
const modalTextarea = document.getElementById('modalKomentar');
if (modalTextarea) {
    modalTextarea.addEventListener('input', () => {
        document.getElementById('modalCharCount').textContent = modalTextarea.value.length;
    });
}

// Close Modal Handler
function closeModal() {
    document.getElementById('reviewModal').classList.remove('active');
    // Reload page to display the submitted review state inline
    setTimeout(() => {
        window.location.reload();
    }, 450);
}

// Submit Modal Review
function submitModalReview() {
    if (modalSelectedRating === 0) {
        alert('Mohon pilih rating bintang terlebih dahulu!');
        return;
    }
    
    const btn = document.getElementById('modalSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    const formData = new FormData();
    formData.append('transaksi_id', '<?= $data["id"]; ?>');
    formData.append('username', '<?= addslashes($data["username"]); ?>');
    formData.append('game', '<?= addslashes($data["game"]); ?>');
    formData.append('rating', modalSelectedRating);
    formData.append('komentar', modalTextarea.value);

    fetch('submit_review.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Switch to success screen
                document.getElementById('modalFormContainer').style.display = 'none';
                document.getElementById('modalSuccessContainer').style.display = 'block';
                spawnConfetti();
            } else {
                btn.disabled = false;
                btn.textContent = '🚀 KIRIM ULASAN SEKARANG';
                alert(res.message);
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = '🚀 KIRIM ULASAN SEKARANG';
            alert('Gagal mengirim ulasan. Silakan coba kembali.');
        });
}
<?php endif; ?>
</script>

<?php if ($snap_token): ?>
<script type="text/javascript"
  src="<?= MIDTRANS_IS_PRODUCTION ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>"
  data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script type="text/javascript">
  // Metode pembayaran yang dipilih user
  let selectedPayMethod = null;

  // Mapping pilihan user ke kode enabledPayments Midtrans
  const methodMap = {
    'qris': ['qris'],
    'bca_va': ['bca_va']
  };

  function selectMethod(method) {
    selectedPayMethod = method;

    // Reset semua card
    document.querySelectorAll('.pay-method-card').forEach(card => {
        card.style.borderColor = '#334155';
        card.style.background = '#1e293b';
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
    });

    // Highlight yang dipilih
    const idMap = { 'qris': 'method-qris', 'bca_va': 'method-bca' };
    const selected = document.getElementById(idMap[method]);
    if (selected) {
        selected.style.borderColor = '#10b981';
        selected.style.background = 'rgba(16, 185, 129, 0.1)';
        selected.style.transform = 'scale(1.03)';
        selected.style.boxShadow = '0 0 0 3px rgba(16,185,129,0.2)';
    }

    // Aktifkan tombol bayar
    const btn = document.getElementById('pay-button');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';
    document.getElementById('pay-hint').style.display = 'none';
  }

  const payBtn = document.getElementById('pay-button');
  if (payBtn) {
      payBtn.addEventListener('click', function () {
        if (!selectedPayMethod) return;
        
        const enabledPayments = methodMap[selectedPayMethod] || [];
        
        snap.pay('<?= $snap_token ?>', {
          enabledPayments: enabledPayments,
          onSuccess: function(result){
            alert("Pembayaran sukses!");
            window.location.reload();
          },
          onPending: function(result){
            alert("Menunggu pembayaran...");
            window.location.reload();
          },
          onError: function(result){
            alert("Pembayaran gagal!");
          },
          onClose: function(){
            console.log('Customer closed the popup without finishing the payment');
          }
        });
      });
  }
</script>
<?php endif; ?>

</body>
</html>