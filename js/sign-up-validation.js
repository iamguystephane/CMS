document.addEventListener("DOMContentLoaded", function () {
  let viewingPassword = false;
  const password = document.querySelector(".password");
  const noEye = document.querySelector(".no-eye");
  const showEye = document.querySelector(".show-eye");

  noEye.addEventListener("click", () => {
    password.type = "text";
    viewingPassword = true;
    toggleIcons();
  });

  showEye.addEventListener("click", () => {
    password.type = "password";
    viewingPassword = false;
    toggleIcons();
  });

  function toggleIcons() {
    if (viewingPassword) {
      noEye.style.display = "none";
      showEye.style.display = "inline";
      showEye.style.display='grid';
      showEye.style.placeContent='center'
    } else {
      noEye.style.display = "inline";
      noEye.style.display='grid';
      noEye.style.placeContent='center'
      showEye.style.display = "none";
    }
  }
  toggleIcons();
  const form = document.querySelector("form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const names = document.querySelector(".names");
    const email = document.querySelector(".email");
    const confirmPassword = document.querySelector(".confirmPassword");
    document.querySelectorAll(".error-message").forEach((el) => el.remove());

    let isValid = true;

    function showError(input, message) {
      const error = document.createElement("p");
      error.className = "error-message text-red-500 text-sm";
      error.textContent = message;
      input.parentNode.appendChild(error);
    }
    if (names.value.trim().length < 3) {
      showError(names, "Full names must be at least 3 characters long.");
      isValid = false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
      showError(email, "Enter a valid email address.");
      isValid = false;
    }
    const passwordError = document.querySelector(".password-error");
    if (password.value.trim().length < 6) {
      passwordError.textContent = "Password cannot be less than 6 characters";
      isValid = false;
    }
    if (password.value.trim() !== confirmPassword.value.trim()) {
      showError(confirmPassword, "Passwords do not match.");
      isValid = false;
    }

    if (isValid) { 
      event.target.submit();
    }
  });
});
