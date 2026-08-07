<template>
  <Card variant="bordered" class="p-6 text-center">
    <div class="text-4xl font-extrabold text-primary-600 dark:text-primary-400">{{ value }}</div>
    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ label }}</p>
  </Card>
</template>

<script setup lang="ts">
import Card from '@/components/ui/Card.vue';

defineProps<{
    value: string | number;
    label: string;
}>();
</script>
