<script setup lang="ts">
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
</script>
<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">GÂY TÊ TỦY SỐNG</h1>
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="overflow-auto">
        <table class="w-full text-sm">
          <thead class="bg-slate-50 text-slate-500 text-xs">
            <tr><th class="text-left p-3">Thuốc</th><th class="text-left p-3">Liều</th><th class="text-left p-3">Ghi chú</th></tr>
          </thead>
          <tbody>
            <tr class="border-t"><td class="p-3 font-medium">Bupivacaine 0.5% heavy</td><td class="p-3">10–15 mg</td><td class="p-3 text-slate-500">Tê mổ bụng dưới, mổ lấy thai</td></tr>
            <tr class="border-t"><td class="p-3 font-medium">Bupivacaine 0.5% isobaric</td><td class="p-3">12–15 mg</td><td class="p-3 text-slate-500">Tê kéo dài hơn</td></tr>
            <tr class="border-t"><td class="p-3 font-medium">Lidocaine 2% heavy</td><td class="p-3">40–60 mg</td><td class="p-3 text-slate-500">Tê ngắn, ngoại trú</td></tr>
            <tr class="border-t"><td class="p-3 font-medium">Fentanyl intrathecal</td><td class="p-3">10–20 mcg</td><td class="p-3 text-slate-500">Tăng giảm đau, ngứa</td></tr>
            <tr class="border-t"><td class="p-3 font-medium">Morphine intrathecal</td><td class="p-3">80–150 mcg</td><td class="p-3 text-slate-500">Giảm đau 12–24h, theo dõi hô hấp</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800">⚠️ Hạ huyết áp thường gặp — bù dịch, phenylephrine sẵn sàng. Chống chỉ định: rối loạn đông máu, nhiễm trùng vị trí chọc.</div>
    <ClinicalDisclaimer />
  </div>
</template>
