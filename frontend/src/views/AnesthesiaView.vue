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
const category = ref('')
const search = ref('')
const loading = ref(false)

async function fetch() {
  loading.value = true
  const params: any = { locale: settings.locale }
  if (category.value) params.category = category.value
  if (search.value) params.search = search.value
  const { data } = await api.get('/drugs', { params })
  drugs.value = data.data || []
  loading.value = false
}

function catLabel(slug: string) {
  const m: any = { 'khoi-me': tx.value('cat_induction'), opioid: 'Opioid', 'gian-co': tx.value('cat_muscle'), 'van-mach': tx.value('cat_vaso'), 'giam-dau': tx.value('tab_pain_relief'), ponv: 'PONV', 'hoa-giai': tx.value('cat_reversal') }
  return m[slug] || slug
}

onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_anesthesia') }}</h1>

    <div class="flex flex-wrap gap-2">
      <select v-model="category" @change="fetch" class="rounded border dark:border-slate-700 px-3 py-1.5 text-sm bg-white dark:bg-slate-800 dark:text-slate-100">
        <option value="">{{ tx('cat_all') }}</option>
        <option value="khoi-me">{{ tx('cat_induction') }}</option>
        <option value="opioid">Opioid</option>
        <option value="gian-co">{{ tx('cat_muscle') }}</option>
        <option value="hoa-giai">{{ tx('cat_reversal') }}</option>
      </select>
      <input v-model="search" @keyup.enter="fetch" :placeholder="tx('drug_search_ph')" class="rounded border dark:border-slate-700 px-3 py-1.5 text-sm flex-1 min-w-[180px] bg-white dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-500" />
      <button @click="fetch" class="px-4 py-1.5 rounded bg-[#0f2a4a] text-white text-sm">{{ tx('common_search') }}</button>
    </div>

    <div v-if="loading" class="text-sm text-slate-400">{{ tx('common_loading') }}</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-1">
        <div class="flex justify-between items-start">
          <div class="font-medium text-sm dark:text-slate-100">{{ d.name }} <span v-if="d.generic_name && d.generic_name!==d.name" class="text-slate-400 font-normal">({{ d.generic_name }})</span></div>
          <span class="text-[11px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 dark:text-slate-300">{{ catLabel(d.category?.slug) }}</span>
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">{{ tx('common_dose') }}: <b>{{ d.dose_text || `${d.dose_min_per_kg}–${d.dose_max_per_kg} ${d.unit}` }}</b> · {{ d.weight_basis }}</div>
        <div v-if="d.preparation" class="text-xs text-slate-500 dark:text-slate-400">{{ tx('common_mix') }}: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs text-amber-600 dark:text-amber-400">⚠️ {{ d.warning }}</div>
      </div>
    </div>
    <div v-if="!loading && !drugs.length" class="text-sm text-slate-400 text-center py-6">{{ tx('common_no_data') }}</div>

    <ClinicalDisclaimer />
  </div>
</template>
