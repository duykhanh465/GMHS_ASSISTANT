<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

defineEmits(['close'])
const router = useRouter()
const auth = useAuthStore()

const menu = [
  { label: 'Tổng quan', path: '/', icon: '◧' },
  { label: 'Bệnh nhân', path: '/patients', icon: '👤' },
  { label: 'Gây mê', path: '/anesthesia', icon: '💉' },
  { label: 'Máy thở', path: '/ventilator', icon: '🫁' },
  { label: 'Vận mạch', path: '/vasopressor', icon: '❤️' },
  { label: 'Giảm đau & PONV', path: '/pain', icon: '💊' },
  { label: 'Gây tê tủy sống', path: '/spinal', icon: '🦴' },
  { label: 'CRISIS', path: '/crisis', icon: '🚨' },
  { label: 'Công thức', path: '/formulas', icon: '📋' },
  { label: 'Tài liệu tham khảo', path: '/references', icon: '📚' },
  { label: 'Cài đặt', path: '/settings', icon: '⚙️' },
]

async function onLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <aside class="w-60 bg-[#0f2a4a] text-white flex flex-col min-h-screen shrink-0">
    <div class="px-4 py-4 border-b border-white/10">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded bg-white/20 flex items-center justify-center text-sm">♡</span>
        <div>
          <div class="font-bold text-sm leading-none">GMHS</div>
          <div class="text-[10px] tracking-widest opacity-70">ASSISTANT</div>
        </div>
      </div>
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
        <span class="w-5 text-center text-xs">{{ item.icon }}</span>
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
