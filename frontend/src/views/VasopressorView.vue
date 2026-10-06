<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'

const drugs = ref<any[]>([])
const search = ref('')
const loading = ref(false)

async function fetch() {
  loading.value = true
  const { data } = await api.get('/drugs', { params: { category: 'van-mach', search: search.value || undefined } })
  drugs.value = data.data || []
  loading.value = false
}

onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">VẬN MẠCH / INOTROPE</h1>

    <div class="flex gap-2">
      <input v-model="search" @keyup.enter="fetch" placeholder="Tìm (noradrenaline...)" class="flex-1 rounded border px-3 py-1.5 text-sm" />
      <button @click="fetch" class="px-4 py-1.5 rounded bg-[#0f2a4a] text-white text-sm">Tìm</button>
    </div>

    <div v-if="loading" class="text-sm text-slate-400">Đang tải...</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white rounded-xl shadow p-4 space-y-1">
        <div class="font-medium text-sm">{{ d.name }} <span class="text-slate-400 font-normal">({{ d.generic_name }})</span></div>
        <div class="text-xs">Liều: <b>{{ d.dose_text }}</b></div>
        <div v-if="d.preparation" class="text-xs text-slate-600">Pha: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded p-1.5">⚠️ {{ d.warning }}</div>
        <div v-for="dil in d.dilutions" :key="dil.id" class="text-xs text-slate-500">• Pha loãng: {{ dil.diluent }} — {{ dil.concentration_display }}</div>
      </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800">
      <b>Lưu ý nồng độ Noradrenaline:</b> 100 mcg/mL (4mg/40mL) ≠ 40 mcg/mL (4mg/100mL). Kiểm tra kỹ nhãn pha trước khi truyền.
    </div>

    <ClinicalDisclaimer />
  </div>
</template>
