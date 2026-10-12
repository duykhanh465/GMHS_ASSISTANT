<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'
import { computed } from 'vue'
import {
  LayoutDashboard,
  Users,
  Syringe,
  Wind,
  HeartPulse,
  Pill,
  Bone,
  Siren,
  Calculator,
  BookOpen,
  Settings,
} from 'lucide-vue-next'
import logoUrl from '@/assets/logo-gmhs.svg'

defineEmits(['close'])
const router = useRouter()
const auth = useAuthStore()
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const menu = computed(() => [
  { label: tx.value('nav_overview'), path: '/', icon: LayoutDashboard },
  { label: tx.value('nav_patients'), path: '/patients', icon: Users },
  { label: tx.value('nav_anesthesia'), path: '/anesthesia', icon: Syringe },
  { label: tx.value('nav_ventilator'), path: '/ventilator', icon: Wind },
  { label: tx.value('nav_vasopressor'), path: '/vasopressor', icon: HeartPulse },
  { label: tx.value('nav_pain'), path: '/pain', icon: Pill },
  { label: tx.value('nav_spinal'), path: '/spinal', icon: Bone },
  { label: tx.value('nav_crisis'), path: '/crisis', icon: Siren },
  { label: tx.value('nav_formulas'), path: '/formulas', icon: Calculator },
  { label: tx.value('nav_references'), path: '/references', icon: BookOpen },
  { label: tx.value('nav_settings'), path: '/settings', icon: Settings },
])

async function onLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <aside class="w-60 bg-[#0f2a4a] text-white flex flex-col min-h-screen shrink-0">
    <div class="px-3 py-3 border-b border-white/10 flex items-center">
      <img :src="logoUrl" alt="GMHS Assistant" class="h-8 w-auto object-contain" />
    </div>

    <nav class="flex-1 py-2 space-y-0.5 overflow-y-auto">
      <router-link
        v-for="item in menu"
        :key="item.path"
        :to="item.path"
        class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-white/10 transition"
        active-class="bg-white/15 font-medium"
        @click="$emit('close')"
      >
        <component :is="item.icon" class="w-4 h-4 shrink-0 opacity-80" />
        <span class="text-[13px]">{{ item.label }}</span>
      </router-link>
    </nav>

    <div class="border-t border-white/10 p-3">
      <div class="flex items-center gap-2 mb-2">
        <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs">Dr</span>
        <div class="text-xs">
          <div class="font-medium">GMHS Assistant</div>
          <div class="opacity-60 text-[11px]">{{ auth.user?.name || 'Dr Tom' }}</div>
        </div>
      </div>
      <button @click="onLogout" class="w-full text-xs py-1.5 rounded bg-white/10 hover:bg-white/20">Đăng xuất</button>
    </div>
  </aside>
</template>
