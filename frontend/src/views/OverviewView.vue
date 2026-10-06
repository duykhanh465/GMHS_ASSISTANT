<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'


interface Patient {
  id: number; code: string; full_name: string; age: number
  gender: string; height_cm: string; weight_kg: string
  asa_class: string; respiratory_condition: string; surgery_note: string
}

const patients = ref<Patient[]>([])
const selectedId = ref<number | null>(null)
const patient = ref<Patient | null>(null)
const calc = ref<any>(null)
const loading = ref(false)
const error = ref('')
const history = ref<any[]>([])

async function fetchPatients() {
  const { data } = await api.get('/patients')
  patients.value = data.data || data // paginate hoặc array
  if (patients.value.length && !selectedId.value) {
    selectedId.value = patients.value[0].id
    await fetchPatient()
  }
}

async function fetchPatient() {
  if (!selectedId.value) return
  const { data } = await api.get(`/patients/${selectedId.value}`)
  patient.value = data
  calc.value = null
  history.value = []
}

async function onCalculate() {
  if (!selectedId.value) return
  loading.value = true
  error.value = ''
  try {
    const { data } = await api.post(`/patients/${selectedId.value}/calculate`)
    calc.value = data
    await fetchHistory()
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Tính toán thất bại'
  } finally { loading.value = false }
}

async function fetchHistory() {
  if (!selectedId.value) return
  const { data } = await api.get(`/patients/${selectedId.value}/calculations`)
  history.value = data.data || []
}

onMounted(fetchPatients)
</script>

<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-lg font-bold text-slate-800">TỔNG QUAN BỆNH NHÂN</h1>
      <select v-model="selectedId" @change="fetchPatient" class="rounded border px-3 py-1.5 text-sm bg-white">
        <option v-for="p in patients" :key="p.id" :value="p.id">{{ p.code }} — {{ p.full_name }}</option>
      </select>
    </div>

    <div v-if="!patient" class="text-sm text-slate-500">Chưa có bệnh nhân. Tạo DEMO-001 trước.</div>

    <template v-else>
      <!-- Thông tin bệnh nhân + Tính toán nhanh -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Card Thông tin -->
        <div class="bg-white rounded-xl shadow p-4 space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold tracking-widest text-slate-500">THÔNG TIN BỆNH NHÂN</h2>
            <span class="text-xs text-slate-400">Sửa</span>
          </div>
          <div class="flex gap-3">
            <div class="w-14 h-14 rounded-full bg-slate-200 flex items-center justify-center text-slate-400">👤</div>
            <div class="text-sm">
              <div class="font-medium">{{ patient.gender === 'female' ? 'Nữ' : 'Nam' }}, {{ patient.age }} tuổi</div>
              <div class="text-slate-500">{{ patient.weight_kg }} kg — {{ patient.height_cm }} cm</div>
              <div class="text-slate-500">ASA {{ patient.asa_class }}</div>
              <div class="text-xs text-slate-400 mt-1">{{ patient.surgery_note || '—' }}</div>
            </div>
          </div>
          <div class="flex gap-2 text-[11px]">
            <span class="px-2 py-1 rounded bg-slate-100">Không thức thuốc</span>
            <span class="px-2 py-1 rounded bg-slate-100">Nhịn ăn 8 giờ</span>
          </div>
        </div>

        <!-- Card Tính toán nhanh -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-4 space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold tracking-widest text-slate-500">TÍNH TOÁN NHANH</h2>
            <button @click="onCalculate" :disabled="loading" class="text-xs px-3 py-1 rounded bg-[#0f2a4a] text-white hover:bg-[#1a3a5c] disabled:opacity-50">
              {{ loading ? 'Đang tính...' : 'Tính toán' }}
            </button>
          </div>

          <div v-if="error" class="text-sm text-red-500">{{ error }}</div>

          <template v-if="calc">
            <div class="grid grid-cols-4 gap-2 text-center">
              <div class="rounded-lg bg-slate-50 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">BMI</div>
                <div class="font-bold text-slate-800">{{ calc.bmi }}</div>
                <div class="text-[10px] text-slate-400">kg/m²</div>
              </div>
              <div class="rounded-lg bg-slate-50 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">PBW</div>
                <div class="font-bold text-slate-800">{{ calc.pbw }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
              <div class="rounded-lg bg-slate-50 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">IBW</div>
                <div class="font-bold text-slate-800">{{ calc.ibw }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
              <div class="rounded-lg bg-amber-50 p-2">
                <div class="text-[10px] tracking-widest text-amber-600">AdjBW</div>
                <div class="font-bold text-amber-700">{{ calc.adjbw }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
            </div>

            <div class="grid grid-cols-4 gap-2 text-center">
              <div class="rounded-lg border p-2">
                <div class="text-[10px] text-slate-400">Dịch 4-2-1</div>
                <div class="font-bold text-[#0f2a4a]">{{ calc.maintenance_fluid }}</div>
                <div class="text-[10px] text-slate-400">mL/giờ</div>
              </div>
              <div class="rounded-lg border p-2">
                <div class="text-[10px] text-slate-400">VT bảo vệ phổi</div>
                <div class="font-bold text-[#0f2a4a]">{{ calc.ventilator_result.vt_min }}–{{ calc.ventilator_result.vt_max }}</div>
                <div class="text-[10px] text-slate-400">mL</div>
              </div>
              <div class="rounded-lg border p-2">
                <div class="text-[10px] text-slate-400">RR gợi ý</div>
                <div class="font-bold text-[#0f2a4a]">{{ calc.ventilator_result.rr }}</div>
                <div class="text-[10px] text-slate-400">lần/phút</div>
              </div>
              <div class="rounded-lg border p-2">
                <div class="text-[10px] text-slate-400">PEEP gợi ý</div>
                <div class="font-bold text-[#0f2a4a]">{{ calc.ventilator_result.peep }}</div>
                <div class="text-[10px] text-slate-400">cmH₂O</div>
              </div>
            </div>
          </template>

          <div v-else class="text-sm text-slate-400 text-center py-6">
            Bấm <b>Tính toán</b> để xem BMI, PBW, dịch, Vt... (khớp Excel)
          </div>
        </div>
      </div>

      <!-- 3 thẻ Gợi ý -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 mb-3">🫁 GỢI Ý MÁY THỞ</h3>
          <div v-if="calc" class="space-y-1.5 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">VT</span><span class="font-medium">{{ calc.ventilator_result.vt_min }}–{{ calc.ventilator_result.vt_max }} mL</span></div>
            <div class="flex justify-between"><span class="text-slate-500">RR</span><span class="font-medium">{{ calc.ventilator_result.rr }} lần/phút</span></div>
            <div class="flex justify-between"><span class="text-slate-500">I:E</span><span class="font-medium">1:2</span></div>
            <div class="flex justify-between"><span class="text-slate-500">PEEP</span><span class="font-medium">{{ calc.ventilator_result.peep }} cmH₂O</span></div>
            <div class="flex justify-between"><span class="text-slate-500">FiO₂</span><span class="font-medium">{{ calc.ventilator_result.fio2 }}</span></div>
          </div>
          <div v-else class="text-sm text-slate-400">Chưa tính</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 mb-3">💉 GỢI Ý GÂY MÊ</h3>
          <div class="text-xs text-slate-400">Lấy từ bảng drugs (18 thuốc) — Phase 4.2. Xem trang Gây mê chi tiết.</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 mb-3">❤️ GỢI Ý VẬN MẠCH</h3>
          <div class="text-xs text-slate-400">Pha: Noradrenaline 4mg/40mL = 100 mcg/mL. Xem trang Vận mạch.</div>
        </div>
      </div>

      <ClinicalDisclaimer />


      <!-- Lịch sử -->
      <div v-if="history.length" class="bg-white rounded-xl shadow p-4">
        <h3 class="text-xs font-bold tracking-widest text-slate-500 mb-2">Lịch sử tính toán ({{ history.length }})</h3>
        <div class="space-y-1 text-xs">
          <div v-for="h in history" :key="h.id" class="flex justify-between border-b py-1">
            <span class="text-slate-500">{{ h.calculated_at }}</span>
            <span>BMI {{ h.bmi }} | PBW {{ h.pbw }} | Vt {{ h.ventilator_result.vt_min }}–{{ h.ventilator_result.vt_max }}</span>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
