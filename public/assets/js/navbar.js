 window.addEventListener("scroll", function() {
            const navbar = document.getElementById("mainNavbar");
            if (window.scrollY > 50) {
                navbar.classList.add("navbar-scrolled");
            } else {
                navbar.classList.remove("navbar-scrolled");
            }
        });



document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.querySelector('[data-bs-toggle="collapse"]');
  const sidebar = document.getElementById("sidebarMenu");

  if (!toggleButton || !sidebar) return;

  toggleButton.addEventListener("click", function (e) {
    e.preventDefault();
    const collapse = bootstrap.Collapse.getOrCreateInstance(sidebar);
    collapse.toggle();
  });
});