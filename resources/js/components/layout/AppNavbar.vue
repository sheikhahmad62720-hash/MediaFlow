<template>
  <header class="sticky top-0 z-20 border-b border-slate-200 dark:border-slate-800 bg-surface/80 backdrop-blur">
    <div class="container-page flex h-16 items-center justify-between">
      <RouterLink to="/" class="flex items-center gap-2"><Logo /></RouterLink>

      <nav class="hidden items-center gap-6 text-sm font-medium md:flex">
        <RouterLink
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          :class="navLinkClass(link.to)"
        >
          {{ link.label }}
        </RouterLink>
      </nav>

      <div class="flex items-center gap-3">
        <ThemeToggle />
        <UserMenu />
        <button
          type="button"
          @click="mobileOpen = true"
          class="focus-ring rounded-md p-2 text-ink hover:bg-slate-100 dark:hover:bg-slate-800 md:hidden"
        >
          <Icon name="menu" :size="22" />
        </button>
      </div>
    </div>
  </header>

  <MobileNav v-model="mobileOpen" />
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';
import ThemeToggle from '@/components/ui/ThemeToggle.vue';
import UserMenu from '@/components/layout/UserMenu.vue';
import MobileNav from '@/components/layout/MobileNav.vue';
import Icon from '@/components/ui/Icon.vue';

const route = useRoute();
const mobileOpen = ref(false);

const links = [
    { to: '/', label: 'Home' },
    { to: '/download', label: 'Download' },
    { to: '/platforms', label: 'Platforms' },
    { to: '/about', label: 'About' },
    { to: '/faq', label: 'FAQ' },
    { to: '/contact', label: 'Contact' },
];

function navLinkClass(to: string) {
    const active = route.path === to;
    return [
        'text-sm font-medium transition-colors',
        active
            ? 'text-primary-600'
            : 'text-slate-700 hover:text-ink dark:text-slate-300 dark:hover:text-slate-100',
    ];
}
</script>