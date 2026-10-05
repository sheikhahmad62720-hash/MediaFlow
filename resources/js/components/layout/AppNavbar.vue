<template>
  <header
    class="sticky top-0 z-20 border-b transition-all duration-300"
    :class="
      scrolled
        ? 'border-slate-200/80 bg-surface/90 shadow-card backdrop-blur-md dark:border-slate-800/80 dark:bg-dark-surface/90'
        : 'border-slate-200 bg-surface/80 backdrop-blur dark:border-slate-800'
    "
  >
    <div class="container-page flex h-16 items-center justify-between">
      <RouterLink to="/" class="focus-ring rounded-md"><Logo /></RouterLink>

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
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';
import ThemeToggle from '@/components/ui/ThemeToggle.vue';
import UserMenu from '@/components/layout/UserMenu.vue';
import MobileNav from '@/components/layout/MobileNav.vue';
import Icon from '@/components/ui/Icon.vue';

const route = useRoute();
const mobileOpen = ref(false);
const scrolled = ref(false);

const links = [
    { to: '/', label: 'Home' },
    { to: '/download', label: 'Download' },
    { to: '/platforms', label: 'Platforms' },
    { to: '/about', label: 'About' },
    { to: '/faq', label: 'FAQ' },
    { to: '/contact', label: 'Contact' },
];

function onScroll() {
    scrolled.value = window.scrollY > 8;
}

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));

function navLinkClass(to: string) {
    const active = route.path === to;
    return [
        'focus-ring relative rounded-md py-1 transition-colors',
        active
            ? 'text-primary-600 dark:text-primary-400 after:absolute after:-bottom-0.5 after:left-0 after:h-0.5 after:w-full after:rounded-full after:bg-primary-600 dark:after:bg-primary-400'
            : 'text-slate-700 hover:text-ink dark:text-slate-300 dark:hover:text-slate-100',
    ];
}
</script>