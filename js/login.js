//=======================================
// TOGGLE PASSWORD
//=======================================

function togglePassword(inputId = "password", button = null) {
  const input = document.getElementById(inputId);

  if (!input) return;

  if (!button) {
    button = input.parentElement.querySelector(".password-toggle");
  }

  if (!button) return;

  const icon = button.querySelector("i");

  if (input.type === "password") {
    input.type = "text";

    icon.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    input.type = "password";

    icon.classList.replace("fa-eye-slash", "fa-eye");
  }
}
//=======================================
// LOGIN ALERTS
//=======================================

document.addEventListener("DOMContentLoaded", () => {
  const errorElement = document.getElementById("loginError");

  if (!errorElement) return;

  const error = errorElement.value;

  switch (error) {
    case "empty_fields":
      AlertService.error("Please enter your email and password.");

      break;

    case "invalid_credentials":
      AlertService.error("Invalid email or password.");

      break;
  }
});
