<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'

const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const sources = ref<any[]>([])
function docTypeLabel(v?: string) {
  if (!v) return '—'
  if (v.includes('Phác đồ khoa')) return tx.value('doc_protocol')
  if (v.includes('Giáo trình')) return tx.value('doc_textbook')
  if (v.includes('Sách dịch')) return tx.value('doc_translated')
  if (v.includes('Quy trình')) return tx.value('doc_procedure')
  if (v.includes('Emergency manual')) return tx.value('doc_manual')
  if (v.includes('Sổ tay')) return tx.value('doc_handbook')
  return v
}
async function fetch() {
  const { data } = await api.get('/sources', { params: { locale: settings.locale } })
  sources.value = Array.isArray(data) ? data : (data.data || [])
}
onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ tx('page_refs') }}</h1>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow dark:shadow-none dark:border dark:border-slate-700 overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-900 text-xs text-slate-500 dark:text-slate-400"><tr><th class="text-left p-3">{{ tx('tbl_doc') }}</th><th class="text-left p-3">{{ tx('tbl_type') }}</th><th class="text-left p-3">{{ tx('tbl_priority') }}</th></tr></thead>
        <tbody>
          <tr v-for="s in sources" :key="s.id" class="border-t dark:border-slate-700 text-xs">
            <td class="p-3"><div class="font-medium dark:text-slate-100">{{ s.name }}</div><div class="text-slate-500 dark:text-slate-400">{{ s.notes || '' }}</div></td>
            <td class="p-3 dark:text-slate-300">{{ docTypeLabel(s.document_type) }}</td>
            <td class="p-3 dark:text-slate-300">{{ s.priority }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!sources.length" class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">{{ tx('common_no_data') }}</div>
    </div>
    <ClinicalDisclaimer />
  </div>
</template>
