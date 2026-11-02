<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const presets = ref<any[]>([])
const scenario = ref('routine')
const loading = ref(false)

async function fetch() {
  loading.value = true
  const { data } = await api.get('/ventilator-presets', { params: { scenario: scenario.value } })
  presets.value = Array.isArray(data) ? data : (data.data || [])
  loading.value = false
}

function label(s: string) { return { routine: tx.value('sc_routine'), asthma_copd: tx.value('sc_asthma'), ards: 'ARDS', co2_endoscopy: tx.value('sc_co2') }[s] || s }

onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_ventilator') }}</h1>

    <div class="flex gap-2 flex-wrap">
      <button v-for="s in ['routine','asthma_copd','ards','co2_endoscopy']" :key="s" @click="scenario=s; fetch()"
        :class="scenario===s ? 'bg-[#0f2a4a] text-white' : 'bg-white dark:bg-slate-800 border dark:border-slate-700 text-slate-700 dark:text-slate-200'"
        class="px-3 py-1.5 rounded text-xs font-medium">
        {{ label(s) }}
      </button>
    </div>

    <div v-if="loading" class="text-sm text-slate-400">{{ tx('common_loading') }}</div>
    <div v-else class="grid grid-cols-2 md:grid-cols-3 gap-3">
      <div v-for="p in presets" :key="p.id" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 text-center">
        <div class="text-[11px] tracking-widest text-slate-400">{{ p.parameter }}</div>
        <div class="font-bold text-slate-800 dark:text-slate-100 mt-1">{{ p.value_display }}</div>
        <div v-if="p.unit" class="text-xs text-slate-400">{{ p.unit }}</div>
        <div v-if="p.warning" class="text-[11px] text-amber-600 dark:text-amber-400 mt-1">⚠️ {{ p.warning }}</div>
      </div>
    </div>

    <div class="text-xs text-slate-400 dark:text-slate-500">Vt = 6–7 mL/kg PBW (routine), {{ tx('vent_note') }}</div>
    <ClinicalDisclaimer />
  </div>
</template>
