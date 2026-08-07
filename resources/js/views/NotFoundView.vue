<template>
  <section class="container-page flex min-h-[60vh] items-center justify-center py-12">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-ink dark:text-slate-100">404</h1>
      <p class="mt-2 text-xl text-slate-600 dark:text-slate-300">The page you are looking for does not exist.</p>
      <RouterLink
        to="/"
        class="mt-6 inline-flex items-center gap-1 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700"
      >
        <Icon name="arrow-left" :size="16" />
        Back to home
      </RouterLink>
    </div>
  </section>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';
import Icon from '@/components/ui/Icon.vue';
</script>
