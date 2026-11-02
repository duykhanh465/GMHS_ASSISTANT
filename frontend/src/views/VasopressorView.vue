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
const search = ref('')
const loading = ref(false)

async function fetch() {
  loading.value = true
  const { data } = await api.get('/drugs', { params: { category: 'van-mach', search: search.value || undefined, locale: settings.locale } })
  drugs.value = data.data || []
  loading.value = false
}

onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_vasopressor') }}</h1>

    <div class="flex gap-2">
      <input v-model="search" @keyup.enter="fetch" :placeholder="tx('drug_search_ph')" class="flex-1 rounded border dark:border-slate-700 px-3 py-1.5 text-sm bg-white dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-500" />
      <button @click="fetch" class="px-4 py-1.5 rounded bg-[#0f2a4a] text-white text-sm">{{ tx('common_search') }}</button>
    </div>

    <div v-if="loading" class="text-sm text-slate-400">{{ tx('common_loading') }}</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="d in drugs" :key="d.id" class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 p-4 space-y-1">
        <div class="font-medium text-sm dark:text-slate-100">{{ d.name }} <span class="text-slate-400 font-normal">({{ d.generic_name }})</span></div>
        <div class="text-xs dark:text-slate-200">{{ tx('common_dose') }}: <b>{{ d.dose_text }}</b></div>
        <div v-if="d.preparation" class="text-xs text-slate-600 dark:text-slate-400">{{ tx('common_mix') }}: {{ d.preparation }}</div>
        <div v-if="d.warning" class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 rounded p-1.5">⚠️ {{ d.warning }}</div>
        <div v-for="dil in d.dilutions" :key="dil.id" class="text-xs text-slate-500 dark:text-slate-400">• {{ tx('common_mix') }}: {{ dil.diluent }} — {{ dil.concentration_display }}</div>
      </div>
    </div>

    <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl p-3 text-xs text-amber-800 dark:text-amber-200">
      <b>{{ tx('vaso_note_title') }}</b> {{ tx('vaso_note') }}
    </div>

    <ClinicalDisclaimer />
  </div>
</template>
