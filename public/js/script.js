// Toggle Navbar on Mobile
const isinavbar = document.querySelector(".isi-navbar");
document.querySelector("#hamburger-menu").onclick = () => {
  isinavbar.classList.toggle("active");
};

// Close Navbar when clicking outside
const hamburger = document.querySelector("#hamburger-menu");
document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !isinavbar.contains(e.target)) {
    isinavbar.classList.remove("active");
  }
});
