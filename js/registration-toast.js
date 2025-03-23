document.addEventListener("DOMContentLoaded", function () {
  // Check if there's a message in the URL (for successful login)
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("success")) {
    showToast("Login successful!", "green");
  }

  // Check for stored error messages (from failed login attempts)
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
  toast.style.color = "white";
  toast.style.display = "block";

  // Hide after 3 seconds
  setTimeout(() => {
    toast.classList.add("hidden");
    toast.style.display = "none";
    toast.classList.add("fade-out");
  }, 3000);
}
