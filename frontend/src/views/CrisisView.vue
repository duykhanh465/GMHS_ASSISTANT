<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const rows = ref<any[]>([])
const loading = ref(false)

async function fetch() {
  loading.value = true
  try {
    const { data } = await api.get('/emergency-protocols', { params: { locale: settings.locale } })
    rows.value = Array.isArray(data) ? data : (data.data || [])
  } finally {
    loading.value = false
  }
}

onMounted(fetch)
watch(() => settings.locale, fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_crisis') }}</h1>
    <div v-if="loading" class="text-sm text-slate-400">{{ tx('common_loading') }}</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="r in rows" :key="r.id" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-2">
        <div class="font-bold text-sm dark:text-slate-100">{{ r.situation }}</div>
        <div v-if="r.signs" class="text-xs text-slate-500 dark:text-slate-400"><b>{{ tx('tbl_signs') }}:</b> {{ r.signs }}</div>
        <div v-if="r.immediate_action" class="text-xs text-slate-700 dark:text-slate-200"><b>{{ tx('tbl_action') }}:</b> {{ r.immediate_action }}</div>
        <div v-if="r.adult_dose" class="text-xs dark:text-slate-200"><b>{{ tx('tbl_adult_dose') }}:</b> {{ r.adult_dose }}</div>
        <div v-if="r.monitoring" class="text-xs text-slate-500 dark:text-slate-400"><b>{{ tx('tbl_monitor') }}:</b> {{ r.monitoring }}</div>
        <div v-if="r.differential" class="text-xs text-slate-500 dark:text-slate-400"><b>{{ tx('tbl_diff') }}:</b> {{ r.differential }}</div>
        <div v-if="r.note" class="text-xs text-amber-700 dark:text-amber-300">⚠️ {{ r.note }}</div>
      </div>
    </div>
    <div v-if="!loading && !rows.length" class="text-sm text-slate-400 text-center py-6">{{ tx('common_no_data') }}</div>
    <ClinicalDisclaimer />
  </div>
</template>
