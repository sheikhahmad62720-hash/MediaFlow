<template>
  <nav
    v-if="links.length"
    class="flex flex-wrap items-center justify-center gap-2 text-sm text-slate-600 dark:text-slate-300"
  >
    <template v-for="link in links" :key="link.label">
      <RouterLink v-if="link.href" :to="link.href" :class="linkClass(link)">
        {{ link.label }}
      </RouterLink>
      <span v-else class="text-slate-300 dark:text-slate-700">/</span>
    </template>
  </nav>
</template>

<script setup lang="ts">
interface Link {
    label: string;
    href?: string;
}

defineProps<{ links: Link[] }>();

function linkClass(link: Link) {
    if (!link.href) return '';
    const active = window.location.pathname === link.href;
    return active
        ? 'text-primary-600 font-medium'
        : 'hover:text-ink dark:hover:text-slate-100 transition-colors';
}
</script>
