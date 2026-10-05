<template>
  <Card
    variant="bordered"
    class="group relative overflow-hidden p-6 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover"
  >
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-500 to-primary-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
    <div class="text-4xl font-extrabold tracking-tight text-gradient">{{ value }}</div>
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
