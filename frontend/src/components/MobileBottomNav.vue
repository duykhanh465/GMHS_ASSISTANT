<template>
  <nav class="fixed bottom-0 left-0 right-0 bg-white border-t flex justify-around py-1.5 z-30">
    <router-link to="/" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>◧</span><span>Tổng quan</span>
    </router-link>
    <router-link to="/anesthesia" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>💉</span><span>Gây mê</span>
    </router-link>
    <router-link to="/ventilator" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>🫁</span><span>Máy thở</span>
    </router-link>
    <router-link to="/patients" class="flex flex-col items-center text-[11px] px-3 py-1" active-class="text-[#0f2a4a] font-medium">
      <span>👤</span><span>Khác</span>
    </router-link>
  </nav>
</template>
