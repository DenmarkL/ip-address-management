import { ref, watch, onMounted } from 'vue'

export function useDarkMode() {
  const isDarkMode = ref(localStorage.getItem('theme') !== 'light') // Default to dark

  const applyTheme = () => {
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
      document.body.style.backgroundColor = '#1a202c'
    } else {
      document.documentElement.classList.remove('dark')
      document.body.style.backgroundColor = 'white'
    }
  }

  const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
    applyTheme()
  }

  watch(isDarkMode, applyTheme)
  onMounted(() => {
    applyTheme() // Apply theme on mount
  })

  return { isDarkMode, toggleTheme }
}
