<?php
$dirs = [
    'c:/xampp/htdocs/akazastore/public/akaza_topup/topup/',
    'c:/xampp/htdocs/akaza_topup/topup/'
];

$files = ['ml.js', 'ff.js', 'mc.js', 'pubg.js'];

$unselectFunc = '
function unselectItem() {
  document.querySelectorAll(".item-card").forEach(c => c.classList.remove("active"));
  selectedProduct = null;
  
  const itemInput = document.getElementById("itemInput");
  if (itemInput) itemInput.value = "";
  const nomInput = document.getElementById("nominalInput");
  if (nomInput) nomInput.value = "";

  const orderEmpty = document.getElementById("orderEmpty");
  const orderDetail = document.getElementById("orderDetail");
  
  if (orderEmpty && orderDetail) {
    orderEmpty.style.display = "block";
    orderDetail.style.display = "none";
  }

  const msoBar = document.getElementById("mobileOrderBar");
  if (msoBar) {
    msoBar.classList.remove("show");
  }
}
';

foreach ($dirs as $dir) {
    foreach ($files as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Check if already modified
            if (strpos($content, 'unselectItem') !== false) {
                echo "Already updated or contains unselectItem: $path\n";
                continue;
            }
            
            // Find createItemCard card.addEventListener('click'...)
            $target = '  card.addEventListener(\'click\', (e) => {
    if (e.target.closest(\'.ic-info\')) return;
    selectItem(card, item);
  });';
            
            $replacement = '  // Info button click handler
  const infoBtn = card.querySelector(\'.ic-info\\');
  if (infoBtn) {
    infoBtn.addEventListener(\'click\', (e) => {
      e.stopPropagation();
      openInfoModal(
        \'Detail Produk\',
        `Nama Produk: ${item.name}\\n` +
        `Estimasi Harga: ${formatRp(item.price)}\\n` +
        `Estimasi Pengiriman: ${badgeText}\\n\\n` +
        `Produk ini akan langsung diproses ke akun game kamu secara otomatis dan aman setelah pembayaran diverifikasi.`
      );
    });
  }

  card.addEventListener(\'click\', (e) => {
    if (e.target.closest(\'.ic-info\')) return;
    if (selectedProduct && selectedProduct.name === item.name) {
      unselectItem();
    } else {
      selectItem(card, item);
    }
  });';
            
            // Clean up slash escaping for PHP replacement
            $replacement = str_replace('\\', '', $replacement);
            
            $new_content = str_replace($target, $replacement, $content);
            
            // Append unselectItem function at the bottom
            $new_content .= "\n" . $unselectFunc;
            
            if ($new_content !== $content) {
                file_put_contents($path, $new_content);
                echo "Successfully updated JS logic in $path\n";
            } else {
                echo "Failed to match click listener target in $path\n";
            }
        }
    }
}
