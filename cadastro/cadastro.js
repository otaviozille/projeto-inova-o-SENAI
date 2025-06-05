// cadastro.js
document.addEventListener("DOMContentLoaded", () => {
  // Elementos DOM
  const darkModeToggle = document.getElementById("darkModeToggle");
  const body = document.body;
  const form = document.getElementById("cadastroForm");
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");

  // Gerenciamento de Tema
  function updateThemeIcon(theme) {
    darkModeToggle.textContent = theme === "dark" ? "🌙" : "☀️";
  }

  // Atualizar tema e fundo
  function updateTheme(theme) {
    if (theme === "dark") {
      body.classList.add("dark-mode");
      body.style.backgroundImage = "url('petrobras-fundo-escuro.jpg')";
    } else {
      body.classList.remove("dark-mode");
      body.style.backgroundImage = "url('petrobras-fundo-claro.jpg')";
    }
    updateThemeIcon(theme);
  }

  // Corrigir evento de troca de tema
  darkModeToggle.addEventListener("click", () => {
    const theme = body.classList.contains("dark-mode") ? "light" : "dark";
    localStorage.setItem("theme", theme);
    updateTheme(theme);
  });

  // Inicializar tema com base no localStorage
  function initializeTheme() {
    const savedTheme = localStorage.getItem("theme") || "light";
    updateTheme(savedTheme);
  }

  // Máscaras de Input
  function initializeMasks() {
    IMask(document.getElementById("cpf"), {
      mask: "000.000.000-00",
      prepare: (value) => value.replace(/\D/g, ""),
    });

    IMask(document.getElementById("telefone"), {
      mask: "(00) 00000-0000",
      prepare: (value) => value.replace(/\D/g, ""),
    });
  }

  // Validações atualizadas
  function validateCPF(cpf) {
    cpf = cpf.replace(/[^\d]/g, "");
    if (cpf.length !== 11 || /^(.)\1+$/.test(cpf)) return false;

    let sum = 0;
    for (let i = 0; i < 9; i++) sum += parseInt(cpf[i]) * (10 - i);
    let digit = 11 - (sum % 11);
    if (digit > 9) digit = 0;
    if (digit !== parseInt(cpf[9])) return false;

    sum = 0;
    for (let i = 0; i < 10; i++) sum += parseInt(cpf[i]) * (11 - i);
    digit = 11 - (sum % 11);
    if (digit > 9) digit = 0;
    return digit === parseInt(cpf[10]);
  }

  function validatePassword(password) {
    // Mínimo 8 caracteres, 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/.test(
      password
    );
  }

  function validateEmail(email) {
    // Aceita qualquer email válido (não apenas petrobras.com.br)
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validatePhone(phone) {
    // Remove tudo exceto números
    phone = phone.replace(/\D/g, "");
    // Aceita formato (XX) XXXXX-XXXX
    return phone.length === 11;
  }

  function validateName(name) {
    // Nome completo com pelo menos duas palavras, sem números ou caracteres especiais
    return /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{3,}(?: [A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+){1,}$/.test(
      name.trim()
    );
  }

  function showError(input, message) {
    const formGroup = input.closest(".form-group");
    const errorElement =
      formGroup.querySelector(".error-message") ||
      createErrorElement(formGroup);
    errorElement.textContent = message;
    input.classList.add("error");
  }

  function createErrorElement(formGroup) {
    const errorElement = document.createElement("small");
    errorElement.className = "error-message";
    formGroup.appendChild(errorElement);
    return errorElement;
  }

  function clearErrors() {
    document.querySelectorAll(".error-message").forEach((el) => el.remove());
    document
      .querySelectorAll(".error")
      .forEach((el) => el.classList.remove("error"));
  }

  // Validação em Tempo Real
  function initializeLiveValidation() {
    form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("input", () => {
        if (input.validity.valid) {
          input.classList.remove("error");
          const errorElement = input
            .closest(".form-group")
            ?.querySelector(".error-message");
          if (errorElement) errorElement.remove();
        }
      });
    });

    confirmPasswordInput.addEventListener("input", () => {
      if (confirmPasswordInput.value !== passwordInput.value) {
        showError(confirmPasswordInput, "As senhas não coincidem");
      }
    });
  }

  // Validação do Formulário
  function validateForm() {
    clearErrors();
    let isValid = true;

    const nomeInput = document.getElementById("nome");
    if (!validateName(nomeInput.value)) {
      showError(nomeInput, "Digite seu nome completo (nome e sobrenome)");
      isValid = false;
    }

    const emailInput = document.getElementById("email");
    if (!validateEmail(emailInput.value)) {
      showError(emailInput, "Digite um e-mail válido");
      isValid = false;
    }

    const cpfInput = document.getElementById("cpf");
    if (!validateCPF(cpfInput.value)) {
      showError(cpfInput, "CPF inválido");
      isValid = false;
    }

    const telefoneInput = document.getElementById("telefone");
    if (!validatePhone(telefoneInput.value)) {
      showError(telefoneInput, "Digite um telefone válido com DDD");
      isValid = false;
    }

    const passwordInput = document.getElementById("password");
    if (!validatePassword(passwordInput.value)) {
      showError(
        passwordInput,
        "A senha deve conter no mínimo 8 caracteres, incluindo maiúscula, minúscula, número e caractere especial"
      );
      isValid = false;
    }

    const confirmPasswordInput = document.getElementById("confirm-password");
    if (passwordInput.value !== confirmPasswordInput.value) {
      showError(confirmPasswordInput, "As senhas não coincidem");
      isValid = false;
    }

    return isValid;
  }

  // Mensagens de Feedback
  function showSuccessMessage(message) {
    const alert = document.createElement("div");
    alert.className = "alert success";
    alert.textContent = message;
    form.prepend(alert);
    setTimeout(() => alert.remove(), 5000);
  }

  function showErrorMessage(message) {
    const alert = document.createElement("div");
    alert.className = "alert error";
    alert.textContent = message;
    form.prepend(alert);
    setTimeout(() => alert.remove(), 5000);
  }

  // Inicialização
  initializeTheme();
  initializeMasks();
  initializeLiveValidation();
});
