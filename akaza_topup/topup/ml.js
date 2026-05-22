let specialItems = [];
let diamonds = [];
let firstTimeItems = [];

const getApiBaseUrl = () => {
    const loc = window.location;
    if (loc.hostname === 'localhost' || loc.hostname === '127.0.0.1') {
        if (loc.pathname.includes('/akazastore/')) {
            return '/akazastore/public/api';
        }
        return '/public/api';
    }
    return '/api';
};
const apiBase = getApiBaseUrl();

const urlParams = new URLSearchParams(window.location.search);
const gameId = urlParams.get('id');

async function fetchProducts() {
    if (!gameId) return;
    
    try {
        const response = await fetch(`${apiBase}/games`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const game = result.data.find(g => g.id == gameId);
            if (game && game.products) {
                // Kelompokkan produk berdasarkan kategori
                specialItems = game.products.filter(p => p.category === 'Special Items');
                diamonds = game.products.filter(p => p.category === 'Diamonds');
                firstTimeItems = game.products.filter(p => p.category === 'Membership'); // Mapping Membership to firstTime section
                
                // Jika Diamonds kosong tapi ada produk lain, masukkan semua ke Diamonds sebagai fallback
                if (diamonds.length === 0 && game.products.length > 0) {
                    diamonds = game.products;
                }
                
                renderProducts();
            }
        }
    } catch (error) {
        console.error('Gagal mengambil data produk:', error);
    }
}

function renderProducts() {
    const specialGrid = document.getElementById('specialItems');
    const diamondGrid = document.getElementById('diamondList');
    const firstTimeGrid = document.getElementById('firstTimeItems');
    
    if (specialGrid) {
        specialGrid.innerHTML = '';
        specialItems.forEach(item => createItemCard(item, specialGrid));
    }
    
    if (diamondGrid) {
        diamondGrid.innerHTML = '';
        diamonds.forEach(item => {
            createItemCard({
                ...item,
                badge: 'instan',
                badgeText: 'Pengiriman INSTAN'
            }, diamondGrid);
        });
    }
    
    if (firstTimeGrid) {
        firstTimeGrid.innerHTML = '';
        firstTimeItems.forEach(item => {
            createItemCard({
                ...item,
                badge: 'proses',
                badgeText: 'Pengiriman INSTAN'
            }, firstTimeGrid);
        });
    }
}

let selectedProduct = null;

const nominalInput = document.getElementById('nominalInput');
const metodeInput = document.getElementById('metodeInput');
const form = document.getElementById('topupForm');

function formatRp(n) {
  return 'Rp ' + n.toLocaleString('id-ID');
}

function createItemCard(item, container) {
  const card = document.createElement('div');
  card.className = 'item-card';
  card.dataset.name = item.name;
  card.dataset.price = item.price;

  const badgeClass = item.badge || 'instan';
  const badgeText = item.badgeText || 'Pengiriman INSTAN';

  card.innerHTML = `
    <div class="ic-name">${item.name}</div>
    <div class="ic-price-row">
      <span class="ic-price-icon">💎</span>
      <span class="ic-price">${formatRp(item.price)}</span>
    </div>
    <div class="ic-footer">
      <button type="button" class="ic-info" title="Info produk">ⓘ</button>
      <div class="ic-badge ${badgeClass}">
        <span class="ic-badge-icon">⚡</span>
        ${badgeText}
      </div>
    </div>
  `;

  card.addEventListener('click', (e) => {
    if (e.target.closest('.ic-info')) return;
    selectItem(card, item);
  });

  container.appendChild(card);
}

function selectItem(card, item) {
  document.querySelectorAll('.item-card').forEach(c => c.classList.remove('active'));
  card.classList.add('active');

  selectedProduct = item;
  const itemInput = document.getElementById('itemInput');
  if (itemInput) itemInput.value = item.name;
  nominalInput.value = item.price;

  const orderEmpty = document.getElementById('orderEmpty');
  const orderDetail = document.getElementById('orderDetail');
  const orderProduct = document.getElementById('orderProduct');
  const orderPrice = document.getElementById('orderPrice');

  if (orderEmpty && orderDetail) {
    orderEmpty.style.display = 'none';
    orderDetail.style.display = 'flex';
    orderProduct.textContent = item.name;
    orderPrice.textContent = formatRp(item.price);
  }

  updateMobileOrderBar();
}

async function fetchPayments() {
    try {
        const response = await fetch(`${apiBase}/payments`);
        const result = await response.json();
        
        if (result.status === 'success') {
            renderPayments(result.data);
        }
    } catch (error) {
        console.error('Gagal mengambil data pembayaran:', error);
    }
}

const paymentSelect = document.getElementById('payment');

function renderPayments(methods) {
    const paymentGrid = document.querySelector('.payment-methods');
    
    if (paymentGrid) {
        paymentGrid.innerHTML = '';
        methods.forEach(method => {
            const div = document.createElement('div');
            div.className = 'payment-option';
            div.dataset.method = method.code;
            div.innerHTML = `
                <div class="pm-icon-wrapper">
                    ${method.image_url ? `<img src="${method.image_url}" class="pm-logo" alt="${method.name}">` : `<span class="pm-icon">🏦</span>`}
                </div>
                <span class="pm-name">${method.name}</span>
            `;
            
            div.addEventListener('click', () => {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                div.classList.add('active');
                paymentSelect.value = method.code;
                if (metodeInput) metodeInput.value = method.code;
            });
            
            paymentGrid.appendChild(div);
        });
    }
    
    if (paymentSelect) {
        paymentSelect.innerHTML = '<option value="">-- Pilih Metode --</option>';
        methods.forEach(method => {
            const opt = document.createElement('option');
            opt.value = method.code;
            opt.textContent = method.name;
            paymentSelect.appendChild(opt);
        });
    }
}

// Inisialisasi pengambilan data
fetchProducts();
fetchPayments();

const btnTriggerModal = document.getElementById('btnTriggerModal');
const btnTriggerMso = document.getElementById('msoBtnTrigger');
const confirmModal = document.getElementById('confirmModal');
const btnCancelModal = document.getElementById('btnCancelModal');
const termsCheck = document.getElementById('termsCheck');
const btnSubmitFinal = document.getElementById('btnSubmitFinal');

const userIdInput = document.getElementById('userid');
const serverInput = document.getElementById('server');
const usernameResult = document.getElementById('usernameResult');
const usernameText = document.getElementById('usernameText');

let checkedUsername = '-';
let checkTimeout = null;

function checkUsername() {
  const uid = userIdInput.value;
  const server = serverInput.value;

  if (uid.length > 3 && server.length > 2) {
    usernameResult.style.display = 'flex';
    usernameResult.className = 'username-result loading';
    usernameText.textContent = 'Memeriksa username...';
    
    const formData = new FormData();
    formData.append('id', uid);
    formData.append('server', server);

    fetch('cek_username.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        checkedUsername = data.username;
        usernameResult.className = 'username-result';
        usernameText.textContent = 'Username: ' + data.username;
      } else {
        checkedUsername = '-';
        usernameResult.className = 'username-result error';
        usernameText.textContent = data.message || 'Player Not Found';
      }
    })
    .catch(err => {
      checkedUsername = '-';
      usernameResult.className = 'username-result error';
      usernameText.textContent = 'Gagal memeriksa username';
    });
  } else {
    usernameResult.style.display = 'none';
    checkedUsername = '-';
  }
}

[userIdInput, serverInput].forEach(input => {
  input.addEventListener('input', () => {
    clearTimeout(checkTimeout);
    checkTimeout = setTimeout(checkUsername, 800);
  });
});

function updateMobileOrderBar() {
  const msoBar = document.getElementById('mobileOrderBar');
  if (selectedProduct) {
    document.getElementById('msoItem').textContent = selectedProduct.name;
    document.getElementById('msoPrice').textContent = formatRp(selectedProduct.price);
    msoBar.classList.add('show');
  }
}



function openConfirmationModal(e) {
  const uid = userIdInput.value;
  const server = serverInput.value;
  const payment = document.getElementById('payment').value;

  if (!uid || !server) {
    alert('Masukkan User ID dan Server terlebih dahulu!');
    return;
  }

  if (!selectedProduct) {
    alert('Pilih nominal terlebih dahulu!');
    return;
  }

  if (!payment) {
    alert('Pilih metode pembayaran terlebih dahulu!');
    return;
  }

  document.getElementById('m_username').textContent = checkedUsername;
  document.getElementById('m_id').textContent = uid;
  document.getElementById('m_server').textContent = server;
  document.getElementById('m_item').textContent = selectedProduct.name;
  
  const paymentName = payment === 'qris' ? 'QRIS' : 'Virtual Account';
  document.getElementById('m_payment').textContent = paymentName;

  confirmModal.classList.add('active');
}

btnTriggerModal.addEventListener('click', openConfirmationModal);
if (btnTriggerMso) {
  btnTriggerMso.addEventListener('click', openConfirmationModal);
}

btnCancelModal.addEventListener('click', () => {
  confirmModal.classList.remove('active');
});

termsCheck.addEventListener('change', function() {
  btnSubmitFinal.disabled = !this.checked;
});

btnSubmitFinal.addEventListener('click', function(e) {
  e.preventDefault();
  form.submit();
});