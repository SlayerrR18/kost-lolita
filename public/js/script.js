// class active

const isinavbar = document.querySelector(".isi-navbar");
document.querySelector("#hamburger-menu").onclick = () => {
  isinavbar.classList.toggle("active");
};

//klik diluar navbar untuk menutup isi navbar
const hamburger = document.querySelector("#hamburger-menu");

document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !isinavbar.contains(e.target)) {
    isinavbar.classList.remove("active");
  }
});
