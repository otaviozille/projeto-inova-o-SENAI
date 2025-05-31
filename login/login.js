document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const togglePassword = document.querySelector(".toggle-password");
  const darkModeToggle = document.getElementById("toggle-theme");
  const body = document.body;

  // Gerenciamento de Tema
  function updateThemeIcon(isDark) {
    darkModeToggle.textContent = isDark ? "🌙" : "☀️";
  }

  // Atualizar tema e fundo
  function updateTheme(isDark) {
    body.classList.toggle("dark", isDark);
    updateThemeIcon(isDark);
  }

  // Corrigir evento de troca de tema
  darkModeToggle.addEventListener("click", () => {
    const isDark = !body.classList.contains("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");
    updateTheme(isDark);
  });

  // Inicializar tema com base no localStorage
  function initializeTheme() {
    const savedTheme = localStorage.getItem("theme") || "light";
    updateTheme(savedTheme === "dark");
  }

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
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
  });

  // Inicializar tema
  initializeTheme();
});
