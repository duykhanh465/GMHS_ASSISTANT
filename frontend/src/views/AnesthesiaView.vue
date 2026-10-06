<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'

const drugs = ref<any[]>([])
const category = ref('')
const search = ref('')
const loading = ref(false)

async function fetch() {
  loading.value = true
  const params: any = {}
  if (category.value) params.category = category.value
  if (search.value) params.search = search.value
  const { data } = await api.get('/drugs', { params })
  drugs.value = data.data || []
  loading.value = false
}

function catLabel(slug: string) {
  const m: any = { 'khoi-me': 'Khởi mê', opioid: 'Opioid', 'gian-co': 'Giãn cơ', 'van-mach': 'Vận mạch', 'giam-dau': 'Giảm đau', ponv: 'PONV', 'hoa-giai': 'Hóa giải' }
  return m[slug] || slug
}

onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">GÂY MÊ</h1>

    <div class="flex flex-wrap gap-2">
      <select v-model="category" @change="fetch" class="rounded border px-3 py-1.5 text-sm bg-white">
        <option value="">Tất cả danh mục</option>
        <option value="khoi-me">Khởi mê</option>
        <option value="opioid">Opioid</option>
        <option value="gian-co">Giãn cơ</option>
        <option value="hoa-giai">Hóa giải</option>
      </select>
      <input v-model="search" @keyup.enter="fetch" placeholder="Tìm thuốc (propofol...)" class="rounded border px-3 py-1.5 text-sm flex-1 min-w-[180px]" />
      <button @click="fetch" class="px-4 py-1.5 rounded bg-[#0f2a4a] text-white text-sm">Tìm</button>
    </div>

    <div v-if="loading" class="text-sm text-slate-400">Đang tải...</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white rounded-xl shadow p-4 space-y-1">
        <div class="flex justify-between items-start">
          <div class="font-medium text-sm">{{ d.name }} <span v-if="d.generic_name && d.generic_name!==d.name" class="text-slate-400 font-normal">({{ d.generic_name }})</span></div>
          <span class="text-[11px] px-2 py-0.5 rounded bg-slate-100">{{ catLabel(d.category?.slug) }}</span>
        </div>
        <div class="text-xs text-slate-600">Liều: <b>{{ d.dose_text || `${d.dose_min_per_kg}–${d.dose_max_per_kg} ${d.unit}` }}</b> · {{ d.weight_basis }}</div>
        <div v-if="d.preparation" class="text-xs text-slate-500">Pha: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs text-amber-600">⚠️ {{ d.warning }}</div>
      </div>
    </div>
    <div v-if="!loading && !drugs.length" class="text-sm text-slate-400 text-center py-6">Không có thuốc phù hợp.</div>

    <ClinicalDisclaimer />
  </div>
</template>
