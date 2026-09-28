import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/client'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('gmhs_token') || '')
  const user = ref<any>(null)

  async function login(email: string, password: string) {
    const { data } = await api.post('/login', { email, password })
    token.value = data.token
    user.value = data.user
    localStorage.setItem('gmhs_token', data.token)
    return data
  }

  async function fetchMe() {
    const { data } = await api.get('/me')
    user.value = data
    return data
  }

  async function logout() {
    try { await api.post('/logout') } catch {}
    token.value = ''
    user.value = null
    localStorage.removeItem('gmhs_token')
  }

  const isLoggedIn = () => !!token.value

  return { token, user, login, fetchMe, logout, isLoggedIn }
})
