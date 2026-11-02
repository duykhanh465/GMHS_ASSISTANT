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
    const { data } = await api.get('/spinal-protocols', { params: { locale: settings.locale } })
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
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_spinal') }}</h1>
    <div v-if="loading" class="text-sm text-slate-400">{{ tx('common_loading') }}</div>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <div class="overflow-auto">
        <table class="w-full text-sm">
          <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 text-xs">
            <tr>
              <th class="text-left p-3">{{ tx('tbl_technique') }}</th>
              <th class="text-left p-3">{{ tx('tbl_adult_dose') }}</th>
              <th class="text-left p-3">{{ tx('tbl_reduced') }}</th>
              <th class="text-left p-3">{{ tx('tbl_prep') }}</th>
              <th class="text-left p-3">{{ tx('tbl_monitor') }}</th>
              <th class="text-left p-3">{{ tx('tbl_warning') }}</th>
            </tr>
          </thead>
          <tbody class="dark:text-slate-200">
            <tr v-for="r in rows" :key="r.id" class="border-t dark:border-slate-700 align-top">
              <td class="p-3 font-medium">{{ r.technique }}</td>
              <td class="p-3">{{ r.adult_dose }}</td>
              <td class="p-3 text-slate-500 dark:text-slate-400">{{ r.reduced_dose }}</td>
              <td class="p-3 text-slate-500 dark:text-slate-400">{{ r.preparation }}</td>
              <td class="p-3 text-slate-500 dark:text-slate-400">{{ r.monitoring }}</td>
              <td class="p-3 text-amber-700 dark:text-amber-300 text-xs">{{ r.warning }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!loading && !rows.length" class="p-6 text-center text-sm text-slate-400">{{ tx('common_no_data') }}</div>
    </div>
    <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl p-3 text-xs text-amber-800 dark:text-amber-200">⚠️ {{ tx('spinal_warn') }}</div>
    <ClinicalDisclaimer />
  </div>
</template>
