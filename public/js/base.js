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
