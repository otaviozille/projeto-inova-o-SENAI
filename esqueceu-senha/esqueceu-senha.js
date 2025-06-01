document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("recoveryForm");
  const emailInput = document.getElementById("email");
  const darkModeToggle = document.getElementById("toggle-theme");
  const body = document.body;

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showError(input, message) {
    const errorElement = input.parentElement.querySelector(".error-message");
    errorElement.textContent = message;
    input.classList.add("error");
  }

  function clearError(input) {
    const errorElement = input.parentElement.querySelector(".error-message");
    errorElement.textContent = "";
    input.classList.remove("error");
  }

  function showMessage(message, type) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `alert alert-${type}`;
    messageDiv.textContent = message;

    const container = document.querySelector(".recovery-box");
    container.insertBefore(messageDiv, form);

    setTimeout(() => messageDiv.remove(), 5000);
  }

  // Validação do formulário
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearError(emailInput);

    if (!validateEmail(emailInput.value)) {
      showError(emailInput, "Por favor, insira um e-mail válido");
      return;
    }

    try {
      const formData = new FormData(form);
      const response = await fetch(form.action, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showMessage(data.message, "success");
        form.reset();
      } else {
        showMessage(data.message, "error");
      }
    } catch (error) {
      showMessage("Erro ao processar a solicitação", "error");
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

  // Inicializar tema
  const savedTheme = localStorage.getItem("theme") === "dark";
  updateTheme(savedTheme);
});
