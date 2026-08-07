<template>
  <div class="flex flex-col items-center justify-center gap-3 py-10">
    <LoadingSpinner :name="name" :size="size" :loading="true" />
    <p v-if="text" class="text-sm text-slate-500 dark:text-slate-400">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';

const props = withDefaults(
    defineProps<{
        name?: string;
        size?: number;
        text?: string;
    }>(),
    { name: 'spinner', size: 32, text: 'Loading...' },
);
</script>
