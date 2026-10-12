<script setup lang="ts">
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
</script>
<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">CÔNG THỨC</h1>
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="text-left p-3">Công thức</th><th class="text-left p-3">Biểu thức</th><th class="text-left p-3">Nguồn</th></tr></thead>
        <tbody class="text-xs">
          <tr class="border-t"><td class="p-3 font-medium">BMI</td><td class="p-3 font-mono">kg / (m)²</td><td class="p-3">Excel Cong_thuc</td></tr>
          <tr class="border-t"><td class="p-3 font-medium">PBW nam</td><td class="p-3 font-mono">50 + 0.91 × (cm − 152.4)</td><td class="p-3">Devine</td></tr>
          <tr class="border-t"><td class="p-3 font-medium">PBW nữ</td><td class="p-3 font-mono">45.5 + 0.91 × (cm − 152.4)</td><td class="p-3">Devine</td></tr>
          <tr class="border-t"><td class="p-3 font-medium">AdjBW</td><td class="p-3 font-mono">PBW + 0.4 × (Actual − PBW) khi &gt;120% PBW</td><td class="p-3">—</td></tr>
          <tr class="border-t"><td class="p-3 font-medium">Dịch 4-2-1</td><td class="p-3 font-mono">40 + 20 + 1×(kg−20) mL/giờ</td><td class="p-3">Holliday-Segar</td></tr>
          <tr class="border-t"><td class="p-3 font-medium">Vt bảo vệ phổi</td><td class="p-3 font-mono">6 mL/kg PBW</td><td class="p-3">ARDSNet</td></tr>
        </tbody>
      </table>
    </div>
    <div class="text-xs text-slate-400">Xem chi tiết: <code>docs/clinical/data-dictionary.md</code></div>
    <ClinicalDisclaimer />
  </div>
</template>
