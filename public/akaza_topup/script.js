(function initTheme() {
  const saved = localStorage.getItem('akaza_theme');
  if (saved) {
    document.documentElement.setAttribute('data-theme', saved);
  }
})();

document.addEventListener('DOMContentLoaded', () => {
  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const current = document.documentElement.getAttribute('data-theme');
      const next = current === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('akaza_theme', next);
    });
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const hamburger = document.getElementById('hamburgerBtn');
  const mobileNav = document.getElementById('mobileNav');
  const overlay = document.getElementById('mobileNavOverlay');
  const closeBtn = document.getElementById('mobileNavClose');

  if (!hamburger || !mobileNav) return;

  function openNav() {
    mobileNav.classList.add('open');
    hamburger.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeNav() {
    mobileNav.classList.remove('open');
    hamburger.classList.remove('active');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', () => {
    if (mobileNav.classList.contains('open')) closeNav();
    else openNav();
  });

  if (overlay) overlay.addEventListener('click', closeNav);
  if (closeBtn) closeBtn.addEventListener('click', closeNav);

  mobileNav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeNav);
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && mobileNav.classList.contains('open')) closeNav();
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const btt = document.getElementById('backToTop');
  if (!btt) return;

  function checkScroll() {
    if (window.scrollY > 300) {
      btt.classList.add('visible');
    } else {
      btt.classList.remove('visible');
    }
  }

  window.addEventListener('scroll', checkScroll, { passive: true });
  checkScroll(); 

  btt.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const href = a.getAttribute('href');
    if (href === '#') return; 
    e.preventDefault();
    const t = document.querySelector(href);
    if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});


const csBtnEl = document.getElementById('csBtn');
if (csBtnEl) {
  csBtnEl.addEventListener('click', () => {
    const url = 'https://wa.me/6282297702711?text=Halo%20CS%20GameTop,%20saya%20mau%20tanya%20...';
    window.open(url, '_blank');
  });
}


const search = document.getElementById('search');
if (search) {
  search.addEventListener('input', (e) => {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#productGrid .prod-card').forEach(card => {
      const title = card.querySelector('.prod-body h3').innerText.toLowerCase();
      card.style.display = title.includes(q) ? '' : 'none';
    });
  });
}


document.addEventListener("DOMContentLoaded", () => {
    const user = JSON.parse(localStorage.getItem("akaza_user"));
    const loggedIn = localStorage.getItem("akaza_loggedIn") === "true";

    const loginBtn = document.getElementById("loginBtn");
    const registerBtn = document.getElementById("registerBtn");
    const userDisplay = document.getElementById("userDisplay");

    if (user && loggedIn) {
        if (loginBtn) loginBtn.remove();
        if (registerBtn) registerBtn.remove();

        if (userDisplay) {
            userDisplay.innerHTML = `
                <span>👤 ${user.username}</span>
                <button id="logoutBtn">Keluar</button>
            `;

            document.getElementById("logoutBtn").addEventListener("click", () => {
                localStorage.setItem("akaza_loggedIn", "false");
                window.location.reload();
            });
        }
    }
});

document.addEventListener('keydown', e => {
  if (e.key === 'Enter' && document.activeElement.classList.contains('prod-card')) {
    document.activeElement.click();
  }
});
