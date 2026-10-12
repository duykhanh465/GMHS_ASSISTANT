<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'

const sources = ref<any[]>([])
async function fetch() {
  const { data } = await api.get('/sources')
  sources.value = Array.isArray(data) ? data : (data.data || [])
}
onMounted(fetch)
</script>

<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">TÀI LIỆU THAM KHẢO</h1>
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="text-left p-3">Tài liệu</th><th class="text-left p-3">Loại</th><th class="text-left p-3">Ưu tiên</th></tr></thead>
        <tbody>
          <tr v-for="s in sources" :key="s.id" class="border-t text-xs">
            <td class="p-3"><div class="font-medium">{{ s.name }}</div><div class="text-slate-500">{{ s.notes || '' }}</div></td>
            <td class="p-3">{{ s.document_type || '—' }}</td>
            <td class="p-3">{{ s.priority }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!sources.length" class="p-6 text-center text-sm text-slate-400">Chưa có nguồn — chạy seeder.</div>
    </div>
    <ClinicalDisclaimer />
  </div>
</template>
