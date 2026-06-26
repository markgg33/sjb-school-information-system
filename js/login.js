//=======================================
// TOGGLE PASSWORD
//=======================================

function togglePassword(inputId, button) {
  const input = document.getElementById(inputId);

  const icon = button.querySelector("i");

  if (input.type === "password") {
    input.type = "text";

    icon.classList.remove("fa-eye");

    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";

    icon.classList.remove("fa-eye-slash");

    icon.classList.add("fa-eye");
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
