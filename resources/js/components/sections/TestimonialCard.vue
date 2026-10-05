<template>
  <Card
    variant="bordered"
    class="relative p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover"
  >
    <svg
      class="absolute right-5 top-5 h-8 w-8 text-primary-100 dark:text-primary-900/50"
      viewBox="0 0 24 24"
      fill="currentColor"
      aria-hidden="true"
    >
      <path d="M7.17 6A5.17 5.17 0 0 0 2 11.17V18h6.5v-6.83H5.33a3.17 3.17 0 0 1 3.17-3.17V6H7.17Zm9 0A5.17 5.17 0 0 0 11 11.17V18h6.5v-6.83h-3.17a3.17 3.17 0 0 1 3.17-3.17V6h-1.5Z" />
    </svg>

    <p class="pr-8 text-sm leading-relaxed italic text-slate-600 dark:text-slate-400">“{{ quote }}”</p>

    <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
      <img
        :src="avatar"
        :alt="name"
        class="h-10 w-10 rounded-full object-cover ring-2 ring-primary-100 dark:ring-primary-900/50"
      />
      <div>
        <p class="text-sm font-semibold text-ink dark:text-slate-100">{{ name }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ role }}</p>
      </div>
    </div>
  </Card>
</template>

<script setup lang="ts">
import Card from '@/components/ui/Card.vue';

defineProps<{
    name: string;
    role: string;
    quote: string;
    avatar: string;
}>();
</script>
