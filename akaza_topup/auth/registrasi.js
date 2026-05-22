document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("regForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const user = {
            username: document.getElementById("username").value.trim(),
            email: document.getElementById("email").value.trim(),
            password: document.getElementById("password").value.trim()
        };

        localStorage.setItem("akaza_user", JSON.stringify(user));

        alert("Akun berhasil dibuat! Silakan login.");
        window.location.href = "login.php";
    });
});
