<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { Wind, Syringe, HeartPulse } from 'lucide-vue-next'
import { useSettingsStore } from '@/stores/settings'
import { useAuthStore } from '@/stores/auth'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const auth = useAuthStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))
const debugUser = computed(() => auth.user ? `${auth.user.email} (${auth.user.role})` : 'chưa load')

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
const drugs = ref<any[]>([])

async function fetchPatients() {
  try {
    const { data } = await api.get('/patients')
    const list = Array.isArray(data) ? data : (data.data ?? [])
    patients.value = list
    const firstPatient = patients.value[0]
    if (firstPatient && !selectedId.value) {
      selectedId.value = firstPatient.id
      await fetchPatient()
    }
    if (!patients.value.length) {
      // still show hint without error so user knows to go create
      error.value = ''
    }
  } catch (e: any) {
    const status = e.response?.status
    console.error('[Overview] GET /patients failed', status, e.response?.data || e.message)
    if (status === 401) error.value = 'Phiên hết hạn — đăng nhập lại.'
    else if (status === 403) error.value = 'Không có quyền xem (403).'
    else error.value = e.response?.data?.message || e.message || 'Không tải được danh sách bệnh nhân'
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
  loading.value = true; error.value = ''
  try {
    const { data } = await api.post(`/patients/${selectedId.value}/calculate`)
    calc.value = data
    await Promise.all([fetchHistory(), fetchDrugs()])
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Tính toán thất bại'
  } finally { loading.value = false }
}

async function fetchHistory() {
  if (!selectedId.value) return
  const { data } = await api.get(`/patients/${selectedId.value}/calculations`)
  history.value = data.data || []
}

async function fetchDrugs() {
  try {
    const { data } = await api.get('/drugs')
    drugs.value = data.data || []
  } catch {}
}

function drugByName(name: string) {
  return drugs.value.find(d => d.name.toLowerCase() === name.toLowerCase())
}
function doseFor(name: string, fallback: string) {
  const d = drugByName(name)
  if (!d) return fallback
  return d.dose_text || fallback
}

const vtDisplay = computed(() => calc.value ? `${calc.value.ventilator_result.vt_min}–${calc.value.ventilator_result.vt_max}` : '—')
const genderLabel = computed(() => {
  if (!patient.value) return '—'
  return patient.value.gender === 'female' ? tx.value('overview_female') : tx.value('overview_male')
})

onMounted(fetchPatients)
</script>

<template>
  <div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('overview_title') }}</h1>
      <select v-model="selectedId" @change="fetchPatient" class="rounded border border-slate-300 dark:border-slate-600 px-3 py-1.5 text-sm bg-white dark:bg-slate-800 dark:text-slate-100 min-w-[240px] max-w-full">
        <option v-if="!patients.length" disabled value="">{{ tx('overview_no_patient') }}</option>
        <option v-for="p in patients" :key="p.id" :value="p.id">{{ p.code }} — {{ p.full_name || '—' }}</option>
      </select>
    </div>

    <div v-if="error && !patient" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded px-3 py-2">{{ error }} <router-link to="/login" class="underline">Đăng nhập lại</router-link> · <router-link to="/patients" class="underline">Sang Bệnh nhân</router-link></div>
    <div v-if="!patient && !error" class="text-sm text-slate-500 dark:text-slate-400 space-y-1">
      <div>{{ tx('overview_no_patient') }}</div>
      <div class="text-xs">Đang đăng nhập: <b>{{ debugUser }}</b> · Tìm thấy {{ patients.length }} case. Nếu là Doctor thì chỉ thấy case do mình tạo; Admin thấy hết. <router-link to="/patients" class="underline text-[#0f2a4a]">Sang Bệnh nhân →</router-link></div>
    </div>

    <template v-if="patient">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-widest text-slate-500 dark:text-slate-400">{{ tx('overview_patient_info') }}</h2>
            <router-link to="/patients" class="text-xs text-[#0f2a4a] dark:text-sky-300 hover:underline">{{ tx('overview_edit') }}</router-link>
          </div>
          <div class="flex gap-3">
            <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-700 border dark:border-slate-600 flex items-center justify-center text-slate-400 shrink-0">👤</div>
            <div class="text-sm dark:text-slate-200">
              <div class="font-medium">{{ genderLabel }}, {{ patient.age }} {{ tx('overview_age') }}</div>
              <div class="text-slate-500 dark:text-slate-400">{{ patient.weight_kg }} kg — {{ patient.height_cm }} cm</div>
              <div class="text-slate-500 dark:text-slate-400">ASA {{ patient.asa_class }}</div>
              <div class="text-xs text-slate-400 mt-1 line-clamp-2">{{ patient.surgery_note || '' }}</div>
            </div>
          </div>
          <div class="flex flex-wrap gap-1.5 text-[11px]">
            <span class="px-2 py-1 rounded bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 text-slate-600 dark:text-slate-300">Không hút thuốc</span>
            <span class="px-2 py-1 rounded bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 text-slate-600 dark:text-slate-300">Không dị ứng thuốc</span>
            <span class="px-2 py-1 rounded bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 text-slate-600 dark:text-slate-300">Nhịn ăn 8 giờ</span>
          </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-widest text-slate-500 dark:text-slate-400">{{ tx('overview_quick_calc') }}</h2>
            <button @click="onCalculate" :disabled="loading" class="text-xs px-3 py-1.5 rounded bg-[#0f2a4a] text-white hover:bg-[#1a3a5c] disabled:opacity-50">
              {{ loading ? tx('overview_calculating') : tx('overview_calculate') }}
            </button>
          </div>

          <div v-if="error" class="text-sm text-red-500">{{ error }}</div>

          <template v-if="calc">
            <div class="grid grid-cols-4 gap-2 text-center">
              <div class="rounded-lg bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_bmi') }}</div>
                <div class="font-bold text-slate-800 dark:text-slate-100">{{ calc.bmi }}</div>
                <div class="text-[10px] text-slate-400">kg/m²</div>
              </div>
              <div class="rounded-lg bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_pbw') }}</div>
                <div class="font-bold text-slate-800 dark:text-slate-100">{{ calc.pbw }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
              <div class="rounded-lg bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_ibw') }}</div>
                <div class="font-bold text-slate-800 dark:text-slate-100">{{ calc.ibw }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
              <div class="rounded-lg bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_lbw') }}</div>
                <div class="font-bold text-slate-800 dark:text-slate-100">{{ calc.dosing_weight }}</div>
                <div class="text-[10px] text-slate-400">kg</div>
              </div>
            </div>
            <div class="grid grid-cols-4 gap-2 text-center">
              <div class="rounded-lg border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_mac') }}</div>
                <div class="font-bold text-[#0f2a4a] dark:text-sky-300">1.15</div>
                <div class="text-[10px] text-slate-400">(40 {{ tx('overview_age') }})</div>
              </div>
              <div class="rounded-lg border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_vt') }}</div>
                <div class="font-bold text-[#0f2a4a] dark:text-sky-300">{{ vtDisplay }}</div>
                <div class="text-[10px] text-slate-400">mL</div>
              </div>
              <div class="rounded-lg border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_rr') }}</div>
                <div class="font-bold text-[#0f2a4a] dark:text-sky-300">{{ calc.ventilator_result.rr }}</div>
                <div class="text-[10px] text-slate-400">/min</div>
              </div>
              <div class="rounded-lg border dark:border-slate-700 p-2">
                <div class="text-[10px] tracking-widest text-slate-400">{{ tx('overview_peep') }}</div>
                <div class="font-bold text-[#0f2a4a] dark:text-sky-300">{{ calc.ventilator_result.peep }}</div>
                <div class="text-[10px] text-slate-400">cmH₂O</div>
              </div>
            </div>
            <div class="flex gap-2 text-[11px] text-slate-500 dark:text-slate-400 justify-end">
              <span>{{ tx('overview_fio2') }} {{ calc.ventilator_result.fio2 }}</span>
              <span>·</span>
              <span>{{ tx('overview_fluid') }}: {{ calc.maintenance_fluid }} mL/h</span>
            </div>
          </template>

          <div v-else class="text-sm text-slate-400 dark:text-slate-500 text-center py-8" v-html="tx('overview_empty_hint')"></div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5"><Wind class="w-4 h-4" /> {{ tx('overview_vent_title') }}</h3>
          <div v-if="calc" class="space-y-1.5 text-sm dark:text-slate-200">
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">VT</span><span class="font-medium">{{ vtDisplay }} mL <span class="text-xs text-slate-400">(6 mL/kg PBW)</span></span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">RR</span><span class="font-medium">{{ calc.ventilator_result.rr }} /min</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">I:E</span><span class="font-medium">1:2</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">PEEP</span><span class="font-medium">{{ calc.ventilator_result.peep }} cmH₂O</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">FiO₂</span><span class="font-medium">{{ calc.ventilator_result.fio2 }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Flow</span><span class="font-medium">2 L/min</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">EtCO₂</span><span class="font-medium">35–45 mmHg</span></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Pplat</span><span class="font-medium">&lt; 30 cmH₂O</span></div>
          </div>
          <div v-else class="text-sm text-slate-400">{{ tx('overview_not_calculated') }}</div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5"><Syringe class="w-4 h-4" /> {{ tx('overview_anesthesia_title') }}</h3>
          <div class="space-y-3 text-xs dark:text-slate-200">
            <div>
              <div class="font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ tx('overview_induction') }}</div>
              <div class="space-y-1">
                <div class="flex justify-between"><span>Fentanyl</span><span class="font-medium">{{ doseFor('Fentanyl','100 mcg') }}</span></div>
                <div class="flex justify-between"><span>Propofol</span><span class="font-medium">{{ doseFor('Propofol','140 mg') }}</span></div>
                <div class="flex justify-between"><span>Rocuronium</span><span class="font-medium">{{ doseFor('Rocuronium','50 mg') }}</span></div>
                <div class="flex justify-between"><span>Lidocaine</span><span class="font-medium">{{ doseFor('Lidocaine','60 mg') }}</span></div>
              </div>
            </div>
            <div>
              <div class="font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ tx('overview_maintenance') }}</div>
              <div class="space-y-1 text-slate-600 dark:text-slate-400">
                <div class="flex justify-between"><span>Sevoflurane</span><span>1.0 – 1.3 MAC</span></div>
                <div class="flex justify-between"><span>Fentanyl</span><span>50 mcg</span></div>
                <div class="flex justify-between"><span>Rocuronium</span><span>10 mg</span></div>
              </div>
            </div>
            <div>
              <div class="font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ tx('overview_emergence') }}</div>
              <div class="space-y-1 text-slate-600 dark:text-slate-400">
                <div class="flex justify-between"><span>Sugammadex</span><span>200 mg</span></div>
                <div class="flex justify-between"><span>Ondansetron</span><span>4 mg</span></div>
                <div class="flex justify-between"><span>Paracetamol</span><span>1 g</span></div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4">
          <h3 class="text-xs font-bold tracking-widest text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5"><HeartPulse class="w-4 h-4" /> {{ tx('overview_vasopressor_title') }}</h3>
          <div class="space-y-3 text-xs dark:text-slate-200">
            <div class="flex justify-between"><span class="font-medium">Phenylephrine</span><span>50 mcg</span></div>
            <div class="text-[11px] text-slate-500">100 mcg/mL — 30 mL/h</div>
            <div class="pt-2 border-t dark:border-slate-700">
              <div class="flex justify-between"><span class="font-medium">Noradrenaline</span><span>0.05 mcg/kg/min</span></div>
              <div class="text-[11px] text-slate-500">4 mg/50 mL — 5.3 mL/h</div>
            </div>
            <div class="pt-2 border-t dark:border-slate-700">
              <div class="flex justify-between"><span class="font-medium">Adrenaline</span><span>0.02 mcg/kg/min</span></div>
              <div class="text-[11px] text-slate-500">1 mg/50 mL — 4.2 mL/h</div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 flex gap-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 p-3">
          <span class="text-amber-600">⚠️</span>
          <div class="text-xs text-amber-800 dark:text-amber-200">
            <div class="font-bold tracking-widest">{{ tx('overview_warning_title') }}</div>
            <div class="mt-1 space-y-0.5">
              <div>• {{ tx('overview_warning_1') }}</div>
              <div>• {{ tx('overview_warning_2') }}</div>
              <div>• {{ tx('overview_warning_3') }}</div>
            </div>
          </div>
        </div>
        <div class="flex gap-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 p-3">
          <span class="text-emerald-600">✓</span>
          <div class="text-xs text-emerald-800 dark:text-emerald-200">
            <div class="font-bold tracking-widest">{{ tx('overview_note_title') }}</div>
            <div class="mt-1">{{ tx('overview_note_text') }}</div>
          </div>
        </div>
      </div>

      <ClinicalDisclaimer />

      <div v-if="history.length" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4">
        <h3 class="text-xs font-bold tracking-widest text-slate-500 dark:text-slate-400 mb-2">{{ tx('overview_history') }} ({{ history.length }})</h3>
        <div class="space-y-1 text-xs dark:text-slate-300">
          <div v-for="h in history" :key="h.id" class="flex justify-between border-b dark:border-slate-700 py-1.5">
            <span class="text-slate-500">{{ h.calculated_at }}</span>
            <span>BMI {{ h.bmi }} | PBW {{ h.pbw }} | Vt {{ h.ventilator_result.vt_min }}–{{ h.ventilator_result.vt_max }}</span>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
