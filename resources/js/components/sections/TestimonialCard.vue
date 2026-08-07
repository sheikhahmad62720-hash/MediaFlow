<template>
  <Card variant="bordered" class="p-5 text-center">
    <img :src="avatar" :alt="name" class="mx-auto h-10 w-10 rounded-full object-cover" />
    <p class="mt-3 text-sm italic text-slate-600 dark:text-slate-400">{{ quote }}</p>
    <p class="mt-3 text-sm font-semibold text-ink dark:text-slate-100">{{ name }}</p>
    <p class="text-xs text-slate-500 dark:text-slate-400">{{ role }}</p>
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
