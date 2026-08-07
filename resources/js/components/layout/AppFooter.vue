<template>
  <footer class="border-t border-slate-200 dark:border-slate-800 bg-surface">
    <div class="container-page py-10 text-sm text-slate-600 dark:text-slate-400">
      <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
        <div class="flex items-center gap-2">
          <Logo />
          <span class="text-slate-500">© {{ year }} MediaFlow.</span>
        </div>
        <div class="flex flex-wrap items-center gap-4">
          <RouterLink to="/privacy">Privacy</RouterLink>
          <RouterLink to="/terms">Terms</RouterLink>
          <RouterLink to="/contact">Contact</RouterLink>
          <span class="text-slate-400">v{{ version }}</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Logo from '@/components/layout/Logo.vue';

const year = computed(() => new Date().getFullYear());
const version = '1.0';
</script>
