<script setup lang="ts">
import { computed } from 'vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))
</script>
<template>
  <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 border-t dark:border-slate-700 flex justify-around py-1.5 z-30 dark:text-slate-200">
    <router-link to="/" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>◧</span><span>{{ tx('nav_overview') }}</span>
    </router-link>
    <router-link to="/anesthesia" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>💉</span><span>{{ tx('nav_anesthesia') }}</span>
    </router-link>
    <router-link to="/ventilator" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>🫁</span><span>{{ tx('nav_ventilator') }}</span>
    </router-link>
    <router-link to="/patients" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>👤</span><span>{{ tx('nav_more') }}</span>
    </router-link>
  </nav>
</template>
