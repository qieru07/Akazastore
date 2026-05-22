<?php
include __DIR__ . "/koneksi.php";
require_once __DIR__ . "/helper.php";

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
            <?php if (isset($pay_info['type']) && $pay_info['type'] == 'qris'): ?>
                <div class="qris-only">
                    <div class="qr-frame">
                        <?php if(!empty($pay_info['image'])): ?>
                            <img src="<?= get_image_base_url(); ?>/payments/<?= $pay_info['image']; ?>" alt="QRIS">
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
        </div>

        <!-- Bottom Warning -->
        <div class="warning-footer">
            Transfer tepat sesuai nominal agar diproses otomatis.
        </div>
    </div>

    <!-- Actions -->
    <div class="actions">
        <button onclick="window.print()" class="btn-primary">Print Invoice</button>
        <a href="index.php" class="btn-secondary">Beranda</a>
    </div>
</div>

<script>
function copyVA() {
    const text = document.getElementById('vaNumber').innerText;
    navigator.clipboard.writeText(text);
    alert('Berhasil disalin!');
}
</script>

</body>
</html>