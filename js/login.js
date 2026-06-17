//=======================================
// TOGGLE PASSOWRD
//=======================================
function togglePassword() {
  const password = document.getElementById("password");

  const icon = document.getElementById("passwordIcon");

  if (password.type === "password") {
    password.type = "text";

    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    password.type = "password";

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
