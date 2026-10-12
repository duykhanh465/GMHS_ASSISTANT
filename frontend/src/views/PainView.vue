<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'

const drugs = ref<any[]>([])
const tab = ref<'all' | 'giam-dau' | 'ponv'>('all')

async function fetch() {
  const category = tab.value === 'all' ? undefined : tab.value
  const params: any = {}
  if (category) params.category = category
  const { data } = await api.get('/drugs', { params })
  let list = data.data || []
  if (tab.value === 'all') list = list.filter((d: any) => ['giam-dau','ponv'].includes(d.category?.slug))
  drugs.value = list
}
onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">GIẢM ĐAU & PONV</h1>
    <div class="flex gap-2">
      <button v-for="t in [{v:'all',l:'Tất cả'},{v:'giam-dau',l:'Giảm đau'},{v:'ponv',l:'PONV'}]" :key="t.v" @click="tab=t.v as any; fetch()" :class="tab===t.v ? 'bg-[#0f2a4a] text-white' : 'bg-white border'" class="px-3 py-1.5 rounded text-xs">{{ t.l }}</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white rounded-xl shadow p-4">
        <div class="font-medium text-sm">{{ d.name }} <span class="text-xs bg-slate-100 px-2 py-0.5 rounded">{{ d.category?.name }}</span></div>
        <div class="text-xs mt-1">Liều: <b>{{ d.dose_text }}</b></div>
        <div v-if="d.preparation" class="text-xs text-slate-500">Pha: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs text-amber-600">⚠️ {{ d.warning }}</div>
      </div>
    </div>
    <div v-if="!drugs.length" class="text-sm text-slate-400 text-center py-6">Không có dữ liệu.</div>
    <ClinicalDisclaimer />
  </div>
</template>
