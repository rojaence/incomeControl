const sidebarToggleOn = document.querySelector('#sidebar-toggle-on')
const sidebarToggleOff = document.querySelector('#sidebar-toggle-off')
const sidebar = document.querySelector('#sidebar')

// Mostrar u ocultar el menú lateral al hacer clic en el botón
sidebarToggleOn.addEventListener('click', () => {
  sidebar.classList.add('sidebar--active')
})

sidebarToggleOff.addEventListener('click', () => {
  sidebar.classList.remove('sidebar--active')
})

// Theme
const getStoredTheme = () => localStorage.getItem('theme')
const setStoredTheme = (theme) => localStorage.setItem('theme', theme)
const matchDarkMode = () => window.matchMedia('(prefers-color-scheme: dark)').matches
const storedTheme = getStoredTheme()

const getPreferredTheme = () => {
  if (storedTheme) {
    return storedTheme
  }
  return matchDarkMode() ? 'dark' : 'light'
}

const setTheme = (theme) => {
  if (theme === 'auto' && matchDarkMode()) {
    document.documentElement.setAttribute('data-bs-theme', 'dark')
  } else {
    document.documentElement.setAttribute('data-bs-theme', theme)
  }
}

setTheme(getPreferredTheme())

const showActiveTheme = (theme) => {
  const iconTheme = {
    auto: 'bi-circle-half',
    dark: 'bi-moon-fill',
    light: 'bi-sun-fill'
  }
  const activeThemeIcon = document.querySelector('#theme-icon-active')
  const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)

  document.querySelectorAll('[data-bs-theme-value]').forEach((element) => {
    element.classList.remove('active')
  })

  btnToActive.classList.add('active')
  activeThemeIcon.classList.remove(iconTheme.auto, iconTheme.light, iconTheme.dark)
  activeThemeIcon.classList.add(iconTheme[theme])
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
  if (storedTheme !== 'light' || storedTheme !== 'dark') {
    setTheme(getPreferredTheme())
  }
})

window.addEventListener('DOMContentLoaded', () => {
  showActiveTheme(getPreferredTheme())

  document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const theme = toggle.getAttribute('data-bs-theme-value')
      localStorage.setItem('theme', theme)
      setTheme(theme)
      showActiveTheme(theme)
    })
  })
})

// Snackbar
document.addEventListener('DOMContentLoaded', () => {
  const toast = document.getElementById('toast')
  if (toast) {
    const toastBootstrap = new bootstrap.Toast(toast)
    toastBootstrap.show()
  }
})
