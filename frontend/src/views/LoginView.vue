<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const auth = useAuthStore()
const email = ref('doctor@gmhs.test')
const password = ref('password')
const error = ref('')
const loading = ref(false)

async function onLogin() {
  error.value = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Đăng nhập thất bại'
  } finally { loading.value = false }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <form @submit.prevent="onLogin" class="w-full max-w-sm bg-white rounded-xl shadow p-6 space-y-4">
      <h1 class="text-xl font-bold text-center text-slate-800">GMHS Assistant</h1>
      <p class="text-sm text-center text-slate-500">Đăng nhập để tiếp tục</p>
      <div>
        <label class="text-sm text-slate-600">Email</label>
        <input v-model="email" type="email" class="mt-1 w-full rounded border px-3 py-2 text-sm" required />
      </div>
      <div>
        <label class="text-sm text-slate-600">Mật khẩu</label>
        <input v-model="password" type="password" class="mt-1 w-full rounded border px-3 py-2 text-sm" required />
      </div>
      <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
      <button type="submit" :disabled="loading" class="w-full rounded bg-[#0f2a4a] py-2 text-sm font-medium text-white hover:bg-[#1a3a5c] disabled:opacity-50">
        {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
      </button>
      <p class="text-xs text-center text-slate-400">doctor@gmhs.test / password</p>
    </form>
  </div>
</template>
