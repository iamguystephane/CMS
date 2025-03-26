document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const menuIcon = document.querySelector(".menu-icon");
  const mainContent = document.querySelector(".main-content");
  const footer = document.querySelector(".footer");

  if (!menuIcon) {
    console.error("Error: .menu-icon not found in the DOM!");
    return; // Stop execution if menuIcon is missing
  }

  menuIcon.addEventListener("click", function () {
    sidebar?.classList.toggle("collapsed");
    mainContent?.classList.toggle("expanded");
    footer?.classList.toggle("expanded");
  });
});
