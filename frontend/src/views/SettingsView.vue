<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useSettingsStore } from '@/stores/settings'
import { t } from '@/i18n'
import type { Locale } from '@/i18n'
import { Languages, Moon, Sun, User, LogOut, Check } from 'lucide-vue-next'

const auth = useAuthStore()
const settings = useSettingsStore()
const tx = computed(() => (k: string) => t(settings.locale as Locale, k))

const localeOptions: { value: Locale; label: string; sub: string; flag: string }[] = [
  { value: 'vi', label: 'Tiếng Việt', sub: 'Vietnamese', flag: '🇻🇳' },
  { value: 'en', label: 'English', sub: 'English', flag: '🇬🇧' },
  { value: 'fr', label: 'Français', sub: 'French', flag: '🇫🇷' },
  { value: 'ja', label: '日本語', sub: 'Japanese', flag: '🇯🇵' },
  { value: 'ko', label: '한국어', sub: 'Korean', flag: '🇰🇷' },
  { value: 'zh', label: '中文', sub: 'Chinese', flag: '🇨🇳' },
  { value: 'de', label: 'Deutsch', sub: 'German', flag: '🇩🇪' },
  { value: 'es', label: 'Español', sub: 'Spanish', flag: '🇪🇸' },
]

function onPick(v: Locale) { settings.setLocale(v) }
</script>

<template>
  <div class="w-full max-w-3xl space-y-5 text-left">
    <div>
      <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
        <Languages class="w-5 h-5 opacity-60" /> {{ tx('settings_title') }}
      </h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ tx('settings_language_hint') }}</p>
    </div>

    <!-- Language: grid cards -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border dark:border-slate-700 p-5">
      <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2">
        <Languages class="w-4 h-4" /> {{ tx('settings_language') }}
      </h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-3">
        <button
          v-for="o in localeOptions" :key="o.value"
          @click="onPick(o.value)"
          :class="settings.locale === o.value
            ? 'bg-[#0f2a4a] text-white border-[#0f2a4a] dark:bg-sky-700 dark:border-sky-700'
            : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'"
          class="relative rounded-xl border px-3 py-3 text-left transition flex flex-col gap-0.5"
        >
          <span class="text-lg leading-none">{{ o.flag }}</span>
          <span class="text-[13px] font-medium leading-tight">{{ o.label }}</span>
          <span class="text-[11px] opacity-60 leading-none">{{ o.sub }}</span>
          <Check v-if="settings.locale === o.value" class="w-4 h-4 absolute top-2 right-2 opacity-90" />
        </button>
      </div>
      <div class="mt-4 rounded-xl bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 px-3 py-2.5 flex gap-2.5 items-start">
        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 shrink-0">{{ tx('settings_preview') }}:</span>
        <span class="text-xs text-slate-500 dark:text-slate-400">{{ tx('settings_preview_text') }} — <b class="text-slate-700 dark:text-slate-200">{{ tx('overview_title') }}</b> · <b class="text-slate-700 dark:text-slate-200">{{ tx('nav_patients') }}</b></span>
      </div>
    </div>

    <!-- Appearance -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border dark:border-slate-700 p-5">
      <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2">
        <Sun v-if="!settings.darkMode" class="w-4 h-4" /><Moon v-else class="w-4 h-4" /> {{ tx('settings_theme') }}
      </h2>
      <div class="mt-3 flex items-center justify-between rounded-xl border dark:border-slate-700 px-4 py-3"
           :class="settings.darkMode ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-700'">
        <div class="flex items-center gap-3">
          <span :class="settings.darkMode ? 'bg-white/15' : 'bg-white border'" class="w-9 h-9 rounded-full flex items-center justify-center">
            <Moon v-if="settings.darkMode" class="w-4 h-4" /><Sun v-else class="w-4 h-4" />
          </span>
          <div>
            <div class="text-sm font-medium">{{ settings.darkMode ? tx('settings_theme_dark') : tx('settings_theme_light') }}</div>
            <div class="text-xs opacity-60">{{ settings.darkMode ? 'Dark' : 'Light' }} · localStorage gmhs_dark</div>
          </div>
        </div>
        <button
          @click="settings.setDarkMode(!settings.darkMode)"
          :class="settings.darkMode ? 'bg-[#0f2a4a] dark:bg-sky-600' : 'bg-slate-300'"
          class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors shrink-0"
          aria-label="toggle dark"
        >
          <span :class="settings.darkMode ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform" />
        </button>
      </div>
    </div>

    <!-- Account -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border dark:border-slate-700 p-5">
      <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2"><User class="w-4 h-4" /> {{ tx('settings_account') }}</h2>
      <div class="mt-3 flex items-center gap-3">
        <span class="w-10 h-10 rounded-full bg-[#0f2a4a] dark:bg-sky-800 text-white flex items-center justify-center text-sm font-medium">
          {{ (auth.user?.name || 'Dr').slice(0,2).toUpperCase() }}
        </span>
        <div class="min-w-0">
          <div class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{{ auth.user?.name || '—' }} <span class="font-normal opacity-60">({{ auth.user?.email || '—' }})</span></div>
          <div class="text-xs text-slate-500">Role: {{ auth.user?.role || '—' }}</div>
        </div>
      </div>
      <button @click="auth.logout(); $router.push('/login')" class="mt-4 w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium flex items-center justify-center gap-2">
        <LogOut class="w-4 h-4" /> {{ tx('settings_logout') }}
      </button>
    </div>

    <p class="text-xs text-slate-400 dark:text-slate-500 text-center">GMHS Assistant — local demo. {{ tx('settings_language') }} & Dark mode → localStorage <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800">gmhs_locale</code> <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800">gmhs_dark</code>.</p>
  </div>
</template>
