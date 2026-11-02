<script setup lang="ts">
import { computed } from 'vue'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))
</script>
<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_formulas') }}</h1>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-900 text-xs text-slate-500 dark:text-slate-400"><tr><th class="text-left p-3">{{ tx('tbl_formula') }}</th><th class="text-left p-3">{{ tx('tbl_expr') }}</th><th class="text-left p-3">{{ tx('tbl_source') }}</th></tr></thead>
        <tbody class="text-xs dark:text-slate-200">
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">BMI</td><td class="p-3 font-mono">kg / (m)²</td><td class="p-3">Excel Cong_thuc</td></tr>
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">{{ tx('form_lb_male') }}</td><td class="p-3 font-mono">50 + 0.91 × (cm − 152.4)</td><td class="p-3">Devine</td></tr>
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">{{ tx('form_lb_female') }}</td><td class="p-3 font-mono">45.5 + 0.91 × (cm − 152.4)</td><td class="p-3">Devine</td></tr>
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">AdjBW</td><td class="p-3 font-mono">PBW + 0.4 × (Actual − PBW) {{ tx('form_when') }}</td><td class="p-3">—</td></tr>
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">{{ tx('form_fluid') }}</td><td class="p-3 font-mono">40 + 20 + 1×(kg−20) mL/h</td><td class="p-3">Holliday-Segar</td></tr>
          <tr class="border-t dark:border-slate-700"><td class="p-3 font-medium">{{ tx('form_lung') }}</td><td class="p-3 font-mono">6 mL/kg PBW</td><td class="p-3">ARDSNet</td></tr>
        </tbody>
      </table>
    </div>
    <div class="text-xs text-slate-400 dark:text-slate-500">{{ tx('form_detail') }} <code>docs/clinical/data-dictionary.md</code></div>
    <ClinicalDisclaimer />
  </div>
</template>
