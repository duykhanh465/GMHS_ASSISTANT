<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import api from '@/api/client'

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
    const msg = e.response?.data?.message || 'Lưu thất bại'
    const errs = e.response?.data?.errors
    error.value = errs ? Object.values(errs).flat().join(' | ') : msg
  } finally { loading.value = false }
}

async function removePatient(p: Patient) {
  if (!confirm(`Xóa ${p.code}?`)) return
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
      <h1 class="text-lg font-bold text-slate-800">BỆNH NHÂN ({{ total }})</h1>
      <button @click="openCreate" class="text-sm px-4 py-1.5 rounded bg-[#0f2a4a] text-white hover:bg-[#1a3a5c]">+ Tạo case</button>
    </div>

    <div class="flex gap-2">
      <input v-model="search" placeholder="Tìm theo mã (DEMO-001)..." class="flex-1 rounded border px-3 py-1.5 text-sm" />
      <button @click="fetchPatients" class="text-sm px-3 py-1.5 rounded border bg-white">Tìm</button>
    </div>

    <!-- Form -->
    <div v-if="showForm" class="bg-white rounded-xl shadow p-4 space-y-3">
      <div class="flex justify-between items-center">
        <h2 class="text-sm font-bold">{{ editing ? 'Sửa' : 'Tạo' }} bệnh nhân</h2>
        <button @click="showForm = false" class="text-sm text-slate-400">Đóng</button>
      </div>
      <div v-if="error" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded p-2">{{ error }}</div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        <label>Mã * <input v-model="form.code" class="w-full rounded border px-2 py-1.5 mt-1" placeholder="DEMO-001" /></label>
        <label>Tên <input v-model="form.full_name" class="w-full rounded border px-2 py-1.5 mt-1" /></label>
        <label>Tuổi * <input v-model.number="form.age" type="number" min="0" max="120" class="w-full rounded border px-2 py-1.5 mt-1" /></label>
        <label>Giới tính
          <select v-model="form.gender" class="w-full rounded border px-2 py-1.5 mt-1">
            <option value="female">Nữ</option><option value="male">Nam</option><option value="other">Khác</option>
          </select>
        </label>
        <label>Cao (cm) * <input v-model.number="form.height_cm" type="number" class="w-full rounded border px-2 py-1.5 mt-1" /></label>
        <label>Nặng (kg) * <input v-model.number="form.weight_kg" type="number" class="w-full rounded border px-2 py-1.5 mt-1" /></label>
        <label>ASA
          <select v-model="form.asa_class" class="w-full rounded border px-2 py-1.5 mt-1">
            <option>I</option><option>II</option><option>III</option><option>IV</option><option>V</option>
          </select>
        </label>
        <label>Bệnh cảnh
          <select v-model="form.respiratory_condition" class="w-full rounded border px-2 py-1.5 mt-1">
            <option value="routine">Thường quy</option><option value="asthma_copd">Hen/COPD</option><option value="ards">ARDS</option><option value="co2_endoscopy">Nội soi CO₂</option>
          </select>
        </label>
        <label class="flex items-center gap-2 mt-6"><input type="checkbox" v-model="form.is_pregnant" /> Có thai</label>
        <label class="sm:col-span-2">Ghi chú <input v-model="form.surgery_note" class="w-full rounded border px-2 py-1.5 mt-1" /></label>
      </div>
      <button @click="submit" :disabled="loading" class="w-full py-2 rounded bg-[#0f2a4a] text-white text-sm disabled:opacity-50">{{ loading ? 'Đang lưu...' : (editing ? 'Cập nhật' : 'Tạo') }}</button>
      <div class="text-[11px] text-slate-400">* Mã duy nhất theo user. Dùng DEMO-001, DEMO-002 để test.</div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div v-if="!patients.length" class="p-6 text-sm text-slate-400 text-center">Chưa có case. Bấm + Tạo case.</div>
      <div v-for="p in patients" :key="p.id" class="flex items-center justify-between border-b p-3 hover:bg-slate-50">
        <div class="text-sm">
          <div class="font-medium">{{ p.code }} — {{ p.full_name || '—' }} <span class="text-slate-400">({{ p.gender === 'female' ? 'Nữ' : 'Nam' }}, {{ p.age }}t)</span></div>
          <div class="text-xs text-slate-500">{{ p.weight_kg }}kg / {{ p.height_cm }}cm · ASA {{ p.asa_class }} · {{ p.respiratory_condition }} · {{ p.created_at.slice(0,10) }}</div>
        </div>
        <div class="flex gap-1">
          <button @click="openEdit(p)" class="text-xs px-2 py-1 rounded border bg-white">Sửa</button>
          <button @click="removePatient(p)" class="text-xs px-2 py-1 rounded border bg-white text-red-600">Xóa</button>
        </div>
      </div>
    </div>
  </div>
</template>
