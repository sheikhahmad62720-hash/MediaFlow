<template>
  <nav
    v-if="meta && meta.last_page > 1"
    class="flex items-center justify-between"
  >
    <RouterLink
      v-if="meta.current_page > 1"
      :to="{ query: { ...query, page: meta.current_page - 1 } }"
      class="rounded-md px-3 py-1 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
    >
      Previous
    </RouterLink>
    <div v-else class="w-[72px]" />

    <span class="text-sm text-slate-500 dark:text-slate-400">
      Page {{ meta.current_page }} of {{ meta.last_page }}
    </span>

    <RouterLink
      v-if="meta.current_page < meta.last_page"
      :to="{ query: { ...query, page: meta.current_page + 1 } }"
      class="rounded-md px-3 py-1 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
    >
      Next
    </RouterLink>
    <div v-else class="w-[56px]" />
  </nav>
</template>

<script setup lang="ts">
import type { PaginationMeta } from '@/types';

defineProps<{
    meta?: PaginationMeta | null;
    query?: Record<string, string>;
}>();
</script>
