<?php
include __DIR__ . "/koneksi.php";

$id = $_GET['id'] ?? null;
$result = null;

if ($id) {
    $id_clean = mysqli_real_escape_string($conn, $id);
    $query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id='$id_clean'");
    $result = mysqli_fetch_assoc($query);
}

function rupiah($angka){
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Transaksi — AkazaStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bg: #0f172a; 
            --card: #1e293b; 
            --primary: #3b82f6; 
            --text: #f1f2f6;
            --accent: #ffa502;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg); 
            color: var(--text); 
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            min-height: 100vh; 
            padding: 20px;
            background-image: radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.1) 0, transparent 50%);
        }
        .cek-container { width: 100%; max-width: 500px; margin-top: 50px; }
        h1 { text-align: center; font-size: 28px; font-weight: 800; margin-bottom: 30px; letter-spacing: -1px; }
        .search-box { 
            background: var(--card); 
            padding: 24px; 
            border-radius: 20px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); 
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .search-box label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .search-box input { 
            width: 100%; 
            background: #0f172a; 
            border: 1px solid #334155; 
            padding: 14px; 
            border-radius: 12px; 
            color: white; 
            font-size: 16px; 
            box-sizing: border-box;
            transition: 0.3s;
        }
        .search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .btn-cek { 
            width: 100%; 
            background: var(--primary); 
            color: white; 
            border: none; 
            padding: 14px; 
            border-radius: 12px; 
            font-weight: 700; 
            margin-top: 20px; 
            cursor: pointer; 
            transition: 0.3s;
            font-size: 15px;
        }
        .btn-cek:hover { background: #2563eb; transform: translateY(-2px); }
        
        .result-card { 
            background: var(--card); 
            padding: 30px; 
            border-radius: 24px; 
            border: 1px solid rgba(255,255,255,0.08); 
            animation: slideUp 0.4s ease-out;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .status-badge { 
            display: inline-block; 
            padding: 6px 14px; 
            border-radius: 100px; 
            font-size: 11px; 
            font-weight: 800; 
            text-transform: uppercase; 
            margin-bottom: 20px; 
            letter-spacing: 0.5px;
        }
        .status-PENDING { background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); }
        .status-Lunas { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .status-Batal { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
        
        .res-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px; }
        .res-label { color: #94a3b8; }
        .res-value { font-weight: 600; color: #f1f2f6; }
        .text-accent { color: var(--accent); }
        
        .no-data { 
            text-align: center; 
            padding: 24px; 
            color: #f87171; 
            background: rgba(239, 68, 68, 0.1); 
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 16px; 
            font-size: 14px;
            font-weight: 600;
        }
        .back-link { display: block; text-align: center; margin-top: 30px; color: #94a3b8; text-decoration: none; font-size: 14px; font-weight: 500; transition: 0.2s; }
        .back-link:hover { color: white; }
    </style>
</head>
<body>

    <div class="cek-container">
        <h1>Riwayat Pesanan 🔍</h1>
        
        <div class="search-box">
            <form action="riwayat.php" method="GET">
                <label>Nomor Transaksi (ID)</label>
                <input type="text" name="id" placeholder="Masukkan ID Pesanan (contoh: 5)" value="<?= htmlspecialchars($id); ?>" required>
                <button type="submit" class="btn-cek">Cek Status Pesanan</button>
            </form>
        </div>

        <?php if ($id): ?>
            <?php if ($result): ?>
                <div class="result-card">
                    <div class="status-badge status-<?= $result['status']; ?>">
                        <?= $result['status']; ?>
                    </div>
                    <div class="res-row">
                        <span class="res-label">ID Transaksi</span>
                        <span class="res-value">#<?= $result['id']; ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">Game</span>
                        <span class="res-value"><?= $result['game']; ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">Produk</span>
                        <span class="res-value text-accent"><?= $result['item']; ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">User ID</span>
                        <span class="res-value"><?= $result['user_id']; ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">Total Tagihan</span>
                        <span class="res-value" style="font-size: 18px; color: #4ade80;">Rp <?= rupiah($result['nominal'] + $result['kode_unik']); ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">Metode</span>
                        <span class="res-value"><?= strtoupper($result['metode']); ?></span>
                    </div>
                    <div class="res-row">
                        <span class="res-label">Waktu Pesan</span>
                        <span class="res-value"><?= date('d M Y, H:i', strtotime($result['tanggal'])); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-data">
                    ❌ Maaf, pesanan dengan ID #<?= htmlspecialchars($id); ?> tidak ditemukan. Silakan cek kembali nomor ID Anda.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <a href="index.php" class="back-link">← Kembali Beranda AkazaStore</a>
    </div>

</body>
</html>