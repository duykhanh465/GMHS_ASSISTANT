<script setup lang="ts">
import { useRoute } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'
const route = useRoute()
const isLogin = () => route.path === '/login'
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
