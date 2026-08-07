<template>
  <div class="flex min-h-screen">
    <aside class="hidden w-64 shrink-0 flex-col gap-2 overflow-y-auto border-r border-slate-200 dark:border-slate-800 bg-surface dark:bg-dark-card p-4 md:flex">
      <div class="mb-6 flex items-center gap-2">
        <Logo />
      </div>
      <nav class="flex flex-col gap-1 text-sm">
        <RouterLink
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          :class="linkClass(link.to)"
        >
          <Icon :name="link.icon" :size="18" />
          <span>{{ link.label }}</span>
        </RouterLink>
      </nav>
    </aside>

    <div class="flex-1 overflow-y-auto">
      <div class="container-page py-6">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';
import Icon from '@/components/ui/Icon.vue';

const route = useRoute();

const links = [
    { to: '/admin/dashboard', label: 'Dashboard', icon: 'home' },
    { to: '/admin/downloads', label: 'Downloads', icon: 'download' },
    { to: '/admin/platforms', label: 'Platforms', icon: 'globe' },
    { to: '/admin/logs', label: 'Activity Logs', icon: 'file-text' },
    { to: '/admin/settings', label: 'Settings', icon: 'cog' },
];

function linkClass(to: string) {
    const isActive = route.fullPath.startsWith(to);
    return [
        'flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition-colors',
        isActive
            ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300'
            : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800',
    ];
}
</script>
