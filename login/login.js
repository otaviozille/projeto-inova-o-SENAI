const form = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const togglePassword = document.querySelector(".toggle-password");
const toggleTheme = document.getElementById("toggle-theme");

// Validação simples
form.addEventListener("submit", function (e) {
  let valid = true;

  if (!emailInput.value.includes("@")) {
    showError(emailInput, "Email inválido");
    valid = false;
  } else {
    clearError(emailInput);
  }

  if (passwordInput.value.length < 6) {
    showError(passwordInput, "Senha deve ter pelo menos 6 caracteres");
    valid = false;
  } else {
    clearError(passwordInput);
  }

  if (!valid) e.preventDefault();
});

function showError(input, message) {
  const error = input.parentElement.querySelector(".error-message");
  error.textContent = message;
  error.style.display = "block";
  input.style.borderColor = "red";
}

function clearError(input) {
  const error = input.parentElement.querySelector(".error-message");
  error.textContent = "";
  error.style.display = "none";
  input.style.borderColor = "";
}

// Mostrar/ocultar senha
togglePassword.addEventListener("click", () => {
  const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);
  togglePassword.classList.toggle("fa-eye");
  togglePassword.classList.toggle("fa-eye-slash");
});

// Modo escuro com troca de fundo e ícone
toggleTheme.addEventListener("click", () => {
  document.body.classList.toggle("dark");
  toggleTheme.textContent = document.body.classList.contains("dark") ? "🌙" : "☀️";
});
