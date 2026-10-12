<script setup lang="ts">
import ClinicalDisclaimer from '@/components/ClinicalDisclaimer.vue'
const cards = [
  { title: 'Ngừng tuần hoàn', color: 'red', steps: ['Gọi hỗ trợ, bắt đầu CPR', 'Adrenaline 1mg IV mỗi 3–5 phút', 'Sốc điện nếu VF/VT', 'Amiodarone 300mg sau sốc lần 3'] },
  { title: 'Sốc phản vệ', color: 'amber', steps: ['Ngừng thuốc nghi ngờ', 'Adrenaline 0.5mg IM (hoặc 50mcg IV)', 'Truyền dịch nhanh, O2 100%', 'Hydrocortisone, antihistamine'] },
  { title: 'Co thắt phế quản', color: 'sky', steps: ['Tăng FiO2, thủ công bóp bóng', 'Salbutamol khí dung/IV', 'Adrenaline nếu nặng', 'Magnesium sulfate nếu kháng trị'] },
  { title: 'Tăng thân nhiệt ác tính', color: 'rose', steps: ['Ngừng sevo, dantrolene 2.5mg/kg', 'Tăng thông khí, hạ thân nhiệt', 'NaHCO3 nếu toan nặng', 'Chuyển ICU'] },
]
</script>
<template>
  <div class="space-y-4">
    <h1 class="text-lg font-bold text-slate-800">CRISIS</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div v-for="c in cards" :key="c.title" class="bg-white rounded-xl shadow p-4">
        <div class="font-bold text-sm mb-2">{{ c.title }}</div>
        <ol class="list-decimal ml-5 space-y-1 text-xs text-slate-600">
          <li v-for="s in c.steps" :key="s">{{ s }}</li>
        </ol>
      </div>
    </div>
    <ClinicalDisclaimer />
  </div>
</template>
