<template>
  <div class="text-center">
    <div class="text-2xl font-bold text-ink dark:text-slate-100">{{ value }}</div>
    <p class="text-xs text-slate-500 dark:text-slate-400">{{ label }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
    label: string;
    value: number | string;
    color?: 'slate' | 'green' | 'red' | 'amber' | 'blue';
}>();
</script>
