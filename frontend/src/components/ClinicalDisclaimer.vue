<script setup lang="ts">
import { computed } from 'vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))
</script>
<template>
  <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 p-3 flex gap-2 text-xs text-amber-800 dark:text-amber-200">
    <span>⚠️</span>
    <div>
      <span class="font-bold">Disclaimer:</span>
      {{ tx('disclaimer_text') }}
    </div>
  </div>
</template>
