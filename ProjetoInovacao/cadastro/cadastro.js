// cadastro.js
document.addEventListener('DOMContentLoaded', () => {
  // Elementos DOM
  const darkModeToggle = document.getElementById('darkModeToggle')
  const body = document.body
  const form = document.getElementById('cadastroForm')
  const passwordInput = document.getElementById('password')
  const confirmPasswordInput = document.getElementById('confirm-password')

  // Gerenciamento de Tema
  function updateThemeIcon(theme) {
    darkModeToggle.textContent = theme === 'dark' ? '🌙' : '☀️'
  }

  // Atualizar tema e fundo
  function updateTheme(theme) {
    if (theme === 'dark') {
      body.classList.add('dark-mode')
      body.style.backgroundImage = "url('petrobras-fundo-escuro.jpg')"
    } else {
      body.classList.remove('dark-mode')
      body.style.backgroundImage = "url('petrobras-fundo-claro.jpg')"
    }
    updateThemeIcon(theme)
  }

  // Corrigir evento de troca de tema
  darkModeToggle.addEventListener('click', () => {
    const theme = body.classList.contains('dark-mode') ? 'light' : 'dark'
    localStorage.setItem('theme', theme)
    updateTheme(theme)
  })

  // Inicializar tema com base no localStorage
  function initializeTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light'
    updateTheme(savedTheme)
  }

  // Máscaras de Input
  function initializeMasks() {
    IMask(document.getElementById('cpf'), {
      mask: '000.000.000-00',
      prepare: (value) => value.replace(/\D/g, ''),
    })

    IMask(document.getElementById('telefone'), {
      mask: '(00) 00000-0000',
      prepare: (value) => value.replace(/\D/g, ''),
    })
  }

  // Validações
  function validateCPF(cpf) {
    cpf = cpf.replace(/\D/g, '')
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false

    let sum = 0
    for (let i = 0; i < 9; i++) {
      sum += parseInt(cpf.charAt(i)) * (10 - i)
    }
    let remainder = 11 - (sum % 11)
    const digit1 = remainder > 9 ? 0 : remainder

    sum = 0
    for (let i = 0; i < 10; i++) {
      sum += parseInt(cpf.charAt(i)) * (11 - i)
    }
    remainder = 11 - (sum % 11)
    const digit2 = remainder > 9 ? 0 : remainder

    return digit1 === parseInt(cpf.charAt(9)) && digit2 === parseInt(cpf.charAt(10))
  }

  function validatePassword(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
    return regex.test(password)
  }

  function showError(input, message) {
    const formGroup = input.closest('.form-group')
    const errorElement = formGroup.querySelector('.error-message') || createErrorElement(formGroup)
    errorElement.textContent = message
    input.classList.add('error')
  }

  function createErrorElement(formGroup) {
    const errorElement = document.createElement('small')
    errorElement.className = 'error-message'
    formGroup.appendChild(errorElement)
    return errorElement
  }

  function clearErrors() {
    document.querySelectorAll('.error-message').forEach((el) => el.remove())
    document.querySelectorAll('.error').forEach((el) => el.classList.remove('error'))
  }

  // Validação em Tempo Real
  function initializeLiveValidation() {
    form.querySelectorAll('input').forEach((input) => {
      input.addEventListener('input', () => {
        if (input.validity.valid) {
          input.classList.remove('error')
          const errorElement = input.closest('.form-group')?.querySelector('.error-message')
          if (errorElement) errorElement.remove()
        }
      })
    })

    confirmPasswordInput.addEventListener('input', () => {
      if (confirmPasswordInput.value !== passwordInput.value) {
        showError(confirmPasswordInput, 'As senhas não coincidem')
      }
    })
  }

  // Validação do Formulário
  function validateForm() {
    clearErrors()
    let isValid = true

    // Validação do Nome
    const nomeInput = document.getElementById('nome')
    if (nomeInput.value.trim().length < 5) {
      showError(nomeInput, 'Nome deve conter pelo menos 5 caracteres')
      isValid = false
    }

    // Validação de E-mail
    const emailInput = document.getElementById('email')
    if (!/^[\w.-]+@petrobras\.com\.br$/.test(emailInput.value)) {
      showError(emailInput, 'E-mail corporativo inválido')
      isValid = false
    }

    // Validação de CPF
    const cpfInput = document.getElementById('cpf')
    if (!validateCPF(cpfInput.value)) {
      showError(cpfInput, 'CPF inválido')
      isValid = false
    }

    // Validação de Telefone
    const telefoneInput = document.getElementById('telefone')
    if (telefoneInput.value.replace(/\D/g, '').length !== 11) {
      showError(telefoneInput, 'Telefone inválido')
      isValid = false
    }

    // Validação de Senha
    if (!validatePassword(passwordInput.value)) {
      showError(
        passwordInput,
        'Senha deve conter pelo menos 8 caracteres, uma letra maiúscula, um número e um caractere especial'
      )
      isValid = false
    }

    // Confirmação de Senha
    if (passwordInput.value !== confirmPasswordInput.value) {
      showError(confirmPasswordInput, 'As senhas não coincidem')
      isValid = false
    }

    return isValid
  }

  // Mensagens de Feedback
  function showSuccessMessage(message) {
    const alert = document.createElement('div')
    alert.className = 'alert success'
    alert.textContent = message
    form.prepend(alert)
    setTimeout(() => alert.remove(), 5000)
  }

  function showErrorMessage(message) {
    const alert = document.createElement('div')
    alert.className = 'alert error'
    alert.textContent = message
    form.prepend(alert)
    setTimeout(() => alert.remove(), 5000)
  }

  // Inicialização
  initializeTheme()
  initializeMasks()
  initializeLiveValidation()
})
