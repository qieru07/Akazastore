document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("loginForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const savedUser = JSON.parse(localStorage.getItem("akaza_user"));
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        if (!savedUser) return alert("Belum ada akun!");

        if (email === savedUser.email && password === savedUser.password) {
            localStorage.setItem("akaza_loggedIn", "true");
            alert("Login berhasil!");
            window.location.href = "../index.php";
        } else {
            alert("Email atau password salah!");
        }
    });
});
