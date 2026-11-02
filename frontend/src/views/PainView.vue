<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const drugs = ref<any[]>([])
const tab = ref<'all' | 'giam-dau' | 'ponv'>('all')
const tabs = computed(() => [
  { v: 'all', l: tx.value('common_all') },
  { v: 'giam-dau', l: tx.value('tab_pain_relief') },
  { v: 'ponv', l: 'PONV' },
])

function catName(slug?: string) {
  if (slug === 'giam-dau') return tx.value('tab_pain_relief')
  if (slug === 'ponv') return 'PONV'
  return slug || ''
}

async function fetch() {
  const category = tab.value === 'all' ? undefined : tab.value
  const params: any = { locale: settings.locale }
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
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_pain') }}</h1>
    <div class="flex gap-2">
      <button v-for="t in tabs" :key="t.v" @click="tab=t.v as any; fetch()" :class="tab===t.v ? 'bg-[#0f2a4a] text-white' : 'bg-white dark:bg-slate-800 border dark:border-slate-700 text-slate-700 dark:text-slate-200'" class="px-3 py-1.5 rounded text-xs">{{ t.l }}</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4">
        <div class="font-medium text-sm dark:text-slate-100">{{ d.name }} <span class="text-xs bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-2 py-0.5 rounded">{{ catName(d.category?.slug) }}</span></div>
        <div class="text-xs mt-1 dark:text-slate-200">{{ tx('common_dose') }}: <b>{{ d.dose_text }}</b></div>
        <div v-if="d.preparation" class="text-xs text-slate-500 dark:text-slate-400">{{ tx('common_mix') }}: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs text-amber-600 dark:text-amber-400">⚠️ {{ d.warning }}</div>
      </div>
    </div>
    <div v-if="!drugs.length" class="text-sm text-slate-400 text-center py-6">{{ tx('common_no_data') }}</div>
    <ClinicalDisclaimer />
  </div>
</template>
