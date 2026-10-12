import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export type Locale = 'vi' | 'en' | 'fr' | 'ja' | 'ko' | 'zh' | 'de' | 'es'

export const useSettingsStore = defineStore('settings', () => {
  const locale = ref<Locale>((localStorage.getItem('gmhs_locale') as Locale) || 'vi')
  const darkMode = ref(localStorage.getItem('gmhs_dark') === '1')

  function setLocale(v: Locale) {
    locale.value = v
    localStorage.setItem('gmhs_locale', v)
    document.documentElement.lang = v
  }

  function setDarkMode(v: boolean) {
    darkMode.value = v
    localStorage.setItem('gmhs_dark', v ? '1' : '0')
    document.documentElement.classList.toggle('dark', v)
  }

  function init() {
    document.documentElement.lang = locale.value
    document.documentElement.classList.toggle('dark', darkMode.value)
  }

  watch(locale, (v) => localStorage.setItem('gmhs_locale', v))
  watch(darkMode, (v) => localStorage.setItem('gmhs_dark', v ? '1' : '0'))

  return { locale, darkMode, setLocale, setDarkMode, init }
})
