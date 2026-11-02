<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'
import { useAuthStore } from '@/stores/auth'
const route = useRoute()
const auth = useAuthStore()
const isLogin = () => route.path === '/login'
onMounted(async () => {
  if (sessionStorage.getItem('gmhs_token') && !auth.user) {
    try { await auth.fetchMe() } catch {}
  }
})
</script>

<template>
  <AppLayout v-if="!isLogin()">
    <router-view />
  </AppLayout>
  <router-view v-else />
</template>

<style>
/* Xóa hết style cũ của Vue — để Tailwind quyết định */
</style>
