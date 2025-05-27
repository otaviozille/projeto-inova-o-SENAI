document.addEventListener('DOMContentLoaded', () => {
const form = document.getElementById('loginForm')
const emailInput = document.getElementById('email')
const passwordInput = document.getElementById('password')
const togglePassword = document.querySelector('.toggle-password')
const toggleTheme = document.getElementById('toggle-theme')

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


// Validação simples
form.addEventListener('submit', function (e) {
  let valid = true

  if (!emailInput.value.includes('@')) {
    showError(emailInput, 'Email inválido')
    valid = false
  } else {
    clearError(emailInput)
  }

  if (passwordInput.value.length < 6) {
    showError(passwordInput, 'Senha deve ter pelo menos 6 caracteres')
    valid = false
  } else {
    clearError(passwordInput)
  }

  if (!valid) e.preventDefault()
})

function showError(input, message) {
  const error = input.parentElement.querySelector('.error-message')
  error.textContent = message
  error.style.display = 'block'
  input.style.borderColor = 'red'
}

function clearError(input) {
  const error = input.parentElement.querySelector('.error-message')
  error.textContent = ''
  error.style.display = 'none'
  input.style.borderColor = ''
}

// Mostrar/ocultar senha
togglePassword.addEventListener('click', () => {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password'
  passwordInput.setAttribute('type', type)
  togglePassword.classList.toggle('fa-eye')
  togglePassword.classList.toggle('fa-eye-slash')
})

// Modo escuro com troca de fundo e ícone + salvar no localStorage
toggleTheme.addEventListener('click', () => {
  document.body.classList.toggle('dark')
  const isDark = document.body.classList.contains('dark')
  toggleTheme.textContent = isDark ? '🌙' : '☀️'
  localStorage.setItem('theme', isDark ? 'dark' : 'light')
})

// Inicializar tema
initializeTheme()
})
