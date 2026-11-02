<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/client'
import { useAuthStore } from '@/stores/auth'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const router = useRouter()
const auth = useAuthStore()
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const tab = ref<'users' | 'drugs' | 'sources'>('users')
const users = ref<any[]>([])
const drugs = ref<any[]>([])
const sources = ref<any[]>([])
const error = ref('')
const loading = ref(false)

const userForm = ref({ name: '', email: '', password: '', role: 'doctor', is_active: true })
const editingUser = ref<any>(null)

async function load() {
  if (auth.user?.role !== 'admin') {
    router.replace('/')
    return
  }
  error.value = ''
  try {
    const [u, d, s] = await Promise.all([
      api.get('/admin/users'),
      api.get('/admin/drugs'),
      api.get('/admin/sources'),
    ])
    users.value = u.data
    drugs.value = d.data
    sources.value = s.data
  } catch (e: any) {
    error.value = e.response?.data?.message || tx.value('admin_forbidden')
  }
}

async function saveUser() {
  loading.value = true
  error.value = ''
  try {
    const payload: any = { ...userForm.value }
    if (editingUser.value && !payload.password) delete payload.password
    if (editingUser.value) await api.put(`/admin/users/${editingUser.value.id}`, payload)
    else await api.post('/admin/users', payload)
    editingUser.value = null
    userForm.value = { name: '', email: '', password: '', role: 'doctor', is_active: true }
    await load()
  } catch (e: any) {
    error.value = e.response?.data?.message || Object.values(e.response?.data?.errors || {}).flat().join(' | ') || tx.value('patients_save_fail')
  } finally {
    loading.value = false
  }
}

function editUser(u: any) {
  editingUser.value = u
  userForm.value = { name: u.name, email: u.email, password: '', role: u.role, is_active: !!u.is_active }
}

async function toggleUser(u: any) {
  await api.put(`/admin/users/${u.id}`, { is_active: !u.is_active })
  await load()
}

async function toggleDrug(d: any) {
  await api.put(`/admin/drugs/${d.id}`, { is_active: !d.is_active })
  await load()
}

onMounted(load)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_admin') }}</h1>
    <div v-if="error" class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 rounded p-2">{{ error }}</div>

    <div class="flex gap-2">
      <button v-for="k in (['users','drugs','sources'] as const)" :key="k" @click="tab=k"
        :class="tab===k ? 'bg-[#0f2a4a] text-white' : 'bg-white dark:bg-slate-800 border dark:border-slate-700 text-slate-700 dark:text-slate-200'"
        class="px-3 py-1.5 rounded text-xs">{{ tx('admin_' + k) }}</button>
    </div>

    <div v-if="tab==='users'" class="space-y-3">
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-3">
        <h2 class="text-sm font-bold dark:text-slate-100">{{ editingUser ? tx('form_update') : tx('admin_create') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm dark:text-slate-200">
          <label>{{ tx('form_name') }} <input v-model="userForm.name" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
          <label>{{ tx('login_email') }} <input v-model="userForm.email" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
          <label>{{ tx('admin_password') }} <input v-model="userForm.password" type="password" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
          <label>{{ tx('admin_role') }}
            <select v-model="userForm.role" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100">
              <option value="doctor">doctor</option>
              <option value="admin">admin</option>
            </select>
          </label>
        </div>
        <button @click="saveUser" :disabled="loading" class="px-4 py-2 rounded bg-[#0f2a4a] text-white text-sm disabled:opacity-50">{{ loading ? tx('form_saving') : tx('admin_save') }}</button>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
        <div v-for="u in users" :key="u.id" class="flex items-center justify-between border-b dark:border-slate-700 p-3 text-sm dark:text-slate-100">
          <div>
            <div class="font-medium">{{ u.name }} <span class="text-slate-400 font-normal">({{ u.email }})</span></div>
            <div class="text-xs text-slate-500">{{ u.role }} · {{ u.is_active ? tx('admin_active') : tx('admin_inactive') }}</div>
          </div>
          <div class="flex gap-1">
            <button @click="editUser(u)" class="text-xs px-2 py-1 rounded border dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100">{{ tx('btn_edit') }}</button>
            <button @click="toggleUser(u)" class="text-xs px-2 py-1 rounded border dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100">{{ u.is_active ? tx('admin_inactive') : tx('admin_active') }}</button>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="tab==='drugs'" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <div v-for="d in drugs" :key="d.id" class="flex items-center justify-between border-b dark:border-slate-700 p-3 text-sm dark:text-slate-100">
        <div>
          <div class="font-medium">{{ d.name }} <span class="text-slate-400 font-normal">{{ d.dose_text }}</span></div>
          <div class="text-xs text-slate-500">{{ d.category?.name }} · {{ d.is_active ? tx('admin_active') : tx('admin_inactive') }}</div>
        </div>
        <button @click="toggleDrug(d)" class="text-xs px-2 py-1 rounded border dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100">{{ d.is_active ? tx('admin_inactive') : tx('admin_active') }}</button>
      </div>
    </div>

    <div v-else class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <div v-for="s in sources" :key="s.id" class="border-b dark:border-slate-700 p-3 text-sm dark:text-slate-100">
        <div class="font-medium">{{ s.name }}</div>
        <div class="text-xs text-slate-500">{{ s.document_type }} · {{ s.priority }}</div>
      </div>
    </div>
  </div>
</template>
