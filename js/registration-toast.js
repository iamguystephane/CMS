document.addEventListener("DOMContentLoaded", function () {
  // Check if there's a message in the URL (for PHP redirects)
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("success")) {
    showToast("Registration successful!", "green");
  }

  // Check for stored messages (for inline PHP messages)
  const storedMessage = localStorage.getItem("toastMessage");
  if (storedMessage) {
    showToast(storedMessage, "red");
    localStorage.removeItem("toastMessage"); // Clear message after displaying
  }
});

function showToast(message, color) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.style.backgroundColor = color === "green" ? "green" : "red";
  toast.classList.remove("hidden");

  // Hide after 3 seconds
  setTimeout(() => {
    toast.classList.add("hidden");
  }, 3000);
}
