import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/client'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(sessionStorage.getItem('gmhs_token') || '')
  const user = ref<any>(null)

  async function login(email: string, password: string) {
    const { data } = await api.post('/login', { email, password })
    token.value = data.token
    user.value = data.user
    sessionStorage.setItem('gmhs_token', data.token)
    sessionStorage.setItem('gmhs_role', data.user?.role || '')
    return data
  }

  async function fetchMe() {
    const { data } = await api.get('/me')
    user.value = data
    if (data?.role) sessionStorage.setItem('gmhs_role', data.role)
    return data
  }

  async function logout() {
    try { await api.post('/logout') } catch {}
    token.value = ''
    user.value = null
    sessionStorage.removeItem('gmhs_token')
    sessionStorage.removeItem('gmhs_role')
  }

  const isLoggedIn = () => !!token.value
  const isAdmin = () => (user.value?.role || sessionStorage.getItem('gmhs_role')) === 'admin'

  return { token, user, login, fetchMe, logout, isLoggedIn, isAdmin }
})
