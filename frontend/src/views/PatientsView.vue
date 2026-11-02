<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue'
import api from '@/api/client'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

interface Patient {
  id: number; code: string; full_name: string; age: number
  gender: string; height_cm: string; weight_kg: string
  asa_class: string; is_pregnant: boolean; respiratory_condition: string
  surgery_note: string; created_at: string
}

const patients = ref<Patient[]>([])
const total = ref(0)
const search = ref('')
const showForm = ref(false)
const editing = ref<Patient | null>(null)
const error = ref('')
const loading = ref(false)

const form = ref({
  code: '', full_name: '', age: 30, gender: 'female',
  height_cm: 150, weight_kg: 65, asa_class: 'II',
  is_pregnant: false, respiratory_condition: 'routine', surgery_note: ''
})

async function fetchPatients() {
  const params: any = { page: 1 }
  if (search.value) params.search = search.value
  const { data } = await api.get('/patients', { params })
  // apiResource paginate: { data: [...], total }
  if (Array.isArray(data)) { patients.value = data; total.value = data.length }
  else { patients.value = data.data; total.value = data.total }
}

function openCreate() {
  editing.value = null
  form.value = { code: '', full_name: '', age: 30, gender: 'female', height_cm: 150, weight_kg: 65, asa_class: 'II', is_pregnant: false, respiratory_condition: 'routine', surgery_note: '' }
  error.value = ''
  showForm.value = true
}
function openEdit(p: Patient) {
  editing.value = p
  form.value = { code: p.code, full_name: p.full_name || '', age: p.age, gender: p.gender, height_cm: Number(p.height_cm), weight_kg: Number(p.weight_kg), asa_class: p.asa_class, is_pregnant: p.is_pregnant, respiratory_condition: p.respiratory_condition, surgery_note: p.surgery_note || '' }
  error.value = ''
  showForm.value = true
}

async function submit() {
  loading.value = true; error.value = ''
  try {
    if (editing.value) {
      await api.put(`/patients/${editing.value.id}`, form.value)
    } else {
      await api.post('/patients', form.value)
    }
    showForm.value = false
    await fetchPatients()
  } catch (e: any) {
    const msg = e.response?.data?.message || tx.value('patients_save_fail')
    const errs = e.response?.data?.errors
    error.value = errs ? Object.values(errs).flat().join(' | ') : msg
  } finally { loading.value = false }
}

async function removePatient(p: Patient) {
  if (!confirm(`${tx.value('patients_delete_q')} ${p.code}?`)) return
  await api.delete(`/patients/${p.id}`)
  await fetchPatients()
}

let timer: any
watch(search, () => { clearTimeout(timer); timer = setTimeout(fetchPatients, 300) })

onMounted(fetchPatients)
</script>

<template>
  <div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_patients') }} ({{ total }})</h1>
      <button @click="openCreate" class="text-sm px-4 py-1.5 rounded bg-[#0f2a4a] text-white hover:bg-[#1a3a5c]">{{ tx('patients_create') }}</button>
    </div>

    <div class="flex gap-2">
      <input v-model="search" :placeholder="tx('patients_search_ph')" class="flex-1 rounded border dark:border-slate-700 px-3 py-1.5 text-sm bg-white dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-500" />
      <button @click="fetchPatients" class="text-sm px-3 py-1.5 rounded border dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-slate-100">{{ tx('common_search') }}</button>
    </div>

    <!-- Form -->
    <div v-if="showForm" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-3">
      <div class="flex justify-between items-center">
        <h2 class="text-sm font-bold dark:text-slate-100">{{ editing ? tx('form_edit_patient') : tx('form_new_patient') }}</h2>
        <button @click="showForm = false" class="text-sm text-slate-400">{{ tx('form_close') }}</button>
      </div>
      <div v-if="error" class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 rounded p-2">{{ error }}</div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm dark:text-slate-200">
        <label>{{ tx('form_code') }} * <input v-model="form.code" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" placeholder="DEMO-001" /></label>
        <label>{{ tx('form_name') }} <input v-model="form.full_name" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
        <label>{{ tx('form_age') }} * <input v-model.number="form.age" type="number" min="0" max="120" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
        <label>{{ tx('form_gender') }}
          <select v-model="form.gender" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100">
            <option value="female">{{ tx('overview_female') }}</option><option value="male">{{ tx('overview_male') }}</option><option value="other">Other</option>
          </select>
        </label>
        <label>{{ tx('form_height') }} * <input v-model.number="form.height_cm" type="number" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
        <label>{{ tx('form_weight') }} * <input v-model.number="form.weight_kg" type="number" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
        <label>ASA
          <select v-model="form.asa_class" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100">
            <option>I</option><option>II</option><option>III</option><option>IV</option><option>V</option>
          </select>
        </label>
        <label>{{ tx('form_condition') }}
          <select v-model="form.respiratory_condition" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100">
            <option value="routine">{{ tx('sc_routine') }}</option><option value="asthma_copd">{{ tx('sc_asthma') }}</option><option value="ards">ARDS</option><option value="co2_endoscopy">{{ tx('sc_co2') }}</option>
          </select>
        </label>
        <label class="flex items-center gap-2 mt-6"><input type="checkbox" v-model="form.is_pregnant" /> {{ tx('form_pregnant') }}</label>
        <label class="sm:col-span-2">{{ tx('form_note') }} <input v-model="form.surgery_note" class="w-full rounded border dark:border-slate-600 px-2 py-1.5 mt-1 bg-white dark:bg-slate-900 dark:text-slate-100" /></label>
      </div>
      <button @click="submit" :disabled="loading" class="w-full py-2 rounded bg-[#0f2a4a] text-white text-sm disabled:opacity-50">{{ loading ? tx('form_saving') : (editing ? tx('form_update') : tx('form_create')) }}</button>
      <div class="text-[11px] text-slate-400">* {{ tx('form_hint_unique') }}</div>
    </div>

    <!-- List -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <div v-if="!patients.length" class="p-6 text-sm text-slate-400 dark:text-slate-500 text-center">{{ tx('form_empty') }}</div>
      <div v-for="p in patients" :key="p.id" class="flex items-center justify-between border-b dark:border-slate-700 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/40">
        <div class="text-sm dark:text-slate-100">
          <div class="font-medium">{{ p.code }} — {{ p.full_name || '—' }} <span class="text-slate-400">({{ p.gender === 'female' ? tx('overview_female') : tx('overview_male') }}, {{ p.age }}{{ tx('overview_age') }})</span></div>
          <div class="text-xs text-slate-500 dark:text-slate-400">{{ p.weight_kg }}kg / {{ p.height_cm }}cm · ASA {{ p.asa_class }} · {{ p.respiratory_condition === 'routine' ? tx('sc_routine') : p.respiratory_condition === 'asthma_copd' ? tx('sc_asthma') : p.respiratory_condition === 'ards' ? tx('sc_ards') : tx('sc_co2') }} · {{ p.created_at.slice(0,10) }}</div>
        </div>
        <div class="flex gap-1">
          <button @click="openEdit(p)" class="text-xs px-2 py-1 rounded border dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-100">{{ tx('btn_edit') }}</button>
          <button @click="removePatient(p)" class="text-xs px-2 py-1 rounded border dark:border-slate-600 bg-white dark:bg-slate-900 text-red-600 dark:text-red-400">{{ tx('btn_delete') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
