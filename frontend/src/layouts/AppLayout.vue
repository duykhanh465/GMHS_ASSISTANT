<script setup lang="ts">
import { ref } from 'vue'
import AppSidebar from '@/components/AppSidebar.vue'
import MobileBottomNav from '@/components/MobileBottomNav.vue'

const drawerOpen = ref(false)
</script>

<template>
  <div class="min-h-screen bg-slate-100 dark:bg-slate-950 flex">
    <!-- Desktop sidebar -->
    <AppSidebar class="hidden lg:flex" />

    <!-- Mobile drawer overlay -->
    <div v-if="drawerOpen" class="fixed inset-0 z-40 lg:hidden">
      <div class="absolute inset-0 bg-black/40" @click="drawerOpen = false"></div>
      <AppSidebar class="relative z-50" @close="drawerOpen = false" />
    </div>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-h-screen">
      <!-- Mobile header -->
      <header class="lg:hidden flex items-center justify-between bg-[#0f2a4a] text-white px-4 py-3">
        <button @click="drawerOpen = true" class="p-1">☰</button>
        <span class="font-bold text-sm">GMHS Assistant</span>
        <span class="w-6"></span>
      </header>

      <main class="flex-1 p-4 lg:p-6 pb-20 lg:pb-6">
        <slot />
      </main>

      <MobileBottomNav class="lg:hidden" />
    </div>
  </div>
</template>
