<?php
include __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth_helper.php";

$username = auth_check();

// Cek apakah sudah login
if (!$username) {
    header("Location: auth/login.php");
    exit;
}

// Ambil riwayat transaksi milik user ini
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE username='$username' ORDER BY id DESC");

function rupiah($angka){
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard <?= htmlspecialchars($username); ?> — AkazaStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0c12; --card: #141824; --primary: #3b82f6; --text: #f1f5f9; --text-dim: #94a3b8; --border: rgba(255, 255, 255, 0.06); }
        body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 40px 20px; display: flex; justify-content: center; }
        .dashboard-container { width: 100%; max-width: 800px; }
        .welcome-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .welcome-header h1 { margin: 0; font-size: 24px; font-weight: 800; }
        .welcome-header p { margin: 4px 0 0; font-size: 14px; color: var(--text-dim); }
        
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--card); padding: 24px; border-radius: 20px; border: 1px solid var(--border); }
        .stat-label { display: block; font-size: 12px; font-weight: 700; color: var(--text-dim); text-transform: uppercase; margin-bottom: 8px; }
        .stat-value { font-size: 20px; font-weight: 800; color: var(--primary); }

        .history-card { background: var(--card); border-radius: 24px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .card-header { padding: 20px 24px; background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--border); font-weight: 700; font-size: 16px; }
        
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { text-align: left; padding: 16px 24px; font-size: 12px; color: var(--text-dim); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 16px 24px; font-size: 14px; border-bottom: 1px solid var(--border); }
        
        .status-badge { padding: 4px 12px; border-radius: 100px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .status-PENDING { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
        .status-Lunas { background: rgba(16, 185, 129, 0.1); color: #4ade80; border: 1px solid rgba(16, 185, 129, 0.2); }
        .status-Batal { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
        
        .btn-view { color: var(--primary); text-decoration: none; font-weight: 700; font-size: 13px; }
        .btn-view:hover { text-decoration: underline; }
        .back-link { display: inline-block; margin-top: 30px; color: var(--text-dim); text-decoration: none; font-size: 14px; font-weight: 600; }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="welcome-header">
            <div>
                <h1>Halo, <?= htmlspecialchars($username); ?>! 👋</h1>
                <p>Selamat datang kembali di dashboard AkazaStore.</p>
            </div>
            <a href="auth/logout.php" style="background: #ef4444; color: white; text-decoration: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 700;">Keluar</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Pesanan</span>
                <span class="stat-value"><?= mysqli_num_rows($query); ?> Pesanan</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Akun Saya</span>
                <span class="stat-value"><?= htmlspecialchars($username); ?></span>
            </div>
        </div>

        <div class="history-card">
            <div class="card-header">Riwayat Top-up Saya</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Game / Item</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td style="font-family: 'Courier New', Courier, monospace; font-weight: 700;">#AKZ-<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div style="font-weight: 700;"><?= $row['game']; ?></div>
                                <div style="font-size: 12px; color: var(--text-dim);"><?= $row['item']; ?></div>
                            </td>
                            <td style="font-weight: 700;">Rp <?= rupiah($row['nominal'] + $row['kode_unik']); ?></td>
                            <td>
                                <span class="status-badge status-<?= $row['status']; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="struk.php?id=<?= $row['id']; ?>" class="btn-view">Detail Struk</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($query) == 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-dim);">Belum ada riwayat transaksi.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="index.php" class="back-link">← Kembali ke Beranda</a>
    </div>

</body>
</html>
