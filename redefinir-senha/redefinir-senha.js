document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("redefinirSenhaForm");
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const toggleButtons = document.querySelectorAll(".toggle-password");
  const darkModeToggle = document.getElementById("toggle-theme");
  const body = document.body;

  function validatePassword(password) {
    // Apenas verifica se tem pelo menos 6 caracteres
    return password.length >= 6;
  }

  function showError(input, message) {
    const formGroup = input.closest(".input-group");
    let errorElement = formGroup.querySelector(".error-message");

    // Criar elemento de erro se não existir
    if (!errorElement) {
      errorElement = document.createElement("small");
      errorElement.className = "error-message";
      formGroup.appendChild(errorElement);
    }

    errorElement.textContent = message;
    errorElement.style.display = "block";
    input.classList.add("error");
  }

  function clearError(input) {
    const formGroup = input.closest(".input-group");
    const errorElement = formGroup.querySelector(".error-message");

    if (errorElement) {
      errorElement.textContent = "";
      errorElement.style.display = "none";
    }

    input.classList.remove("error");
  }

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const input = button.previousElementSibling;
      const type =
        input.getAttribute("type") === "password" ? "text" : "password";
      input.setAttribute("type", type);
      button.classList.toggle("fa-eye");
      button.classList.toggle("fa-eye-slash");
    });
  });

  // Validação do formulário
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    let isValid = true;

    clearError(passwordInput);
    clearError(confirmPasswordInput);

    if (!validatePassword(passwordInput.value)) {
      showError(passwordInput, "A senha deve ter no mínimo 6 caracteres");
      isValid = false;
    }

    if (passwordInput.value !== confirmPasswordInput.value) {
      showError(confirmPasswordInput, "As senhas não coincidem");
      isValid = false;
    }

    if (isValid) {
      form.submit();
    }
  });

  // Tema escuro
  function updateTheme(isDark) {
    body.classList.toggle("dark", isDark);
    darkModeToggle.textContent = isDark ? "🌙" : "☀️";
    localStorage.setItem("theme", isDark ? "dark" : "light");
  }

  darkModeToggle.addEventListener("click", () => {
    const isDark = !body.classList.contains("dark");
    updateTheme(isDark);
  });

  const savedTheme = localStorage.getItem("theme") === "dark";
  updateTheme(savedTheme);
});
