<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const router = useRouter()
const auth = useAuthStore()
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

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
    error.value = e.response?.data?.message || tx.value('login_fail')
  } finally { loading.value = false }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 dark:bg-slate-950 px-4">
    <form @submit.prevent="onLogin" class="w-full max-w-sm bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-6 space-y-4">
      <h1 class="text-xl font-bold text-center text-slate-800 dark:text-slate-100">GMHS Assistant</h1>
      <p class="text-sm text-center text-slate-500 dark:text-slate-400">{{ tx('login_title') }}</p>
      <div>
        <label class="text-sm text-slate-600 dark:text-slate-300">{{ tx('login_email') }}</label>
        <input v-model="email" type="email" class="mt-1 w-full rounded border dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-slate-100" required />
      </div>
      <div>
        <label class="text-sm text-slate-600 dark:text-slate-300">{{ tx('login_password') }}</label>
        <input v-model="password" type="password" class="mt-1 w-full rounded border dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-slate-100" required />
      </div>
      <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
      <button type="submit" :disabled="loading" class="w-full rounded bg-[#0f2a4a] py-2 text-sm font-medium text-white hover:bg-[#1a3a5c] disabled:opacity-50">
        {{ loading ? tx('login_loading') : tx('login_submit') }}
      </button>
      <p class="text-xs text-center text-slate-400">doctor@gmhs.test / password</p>
    </form>
  </div>
</template>
