import './bootstrap';

const navBar = document.querySelector("nav"),
      menuBtns = document.querySelectorAll(".menu-icon"),
      overlay = document.querySelector(".overlay"),
      app = document.querySelector("#app"); // ambil div wrapper

menuBtns.forEach((menuBtn) => {
  menuBtn.addEventListener("click", () => {
    navBar.classList.toggle("open");
    app.classList.toggle("geser-kanan"); // tambahkan class saat open
  });
});

overlay.addEventListener("click", () => {
  navBar.classList.remove("open");
  app.classList.remove("geser-kanan"); // hapus class saat ditutup
});
