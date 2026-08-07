<template>
  <Card variant="bordered" class="p-6 text-center transition-transform hover:-translate-y-0.5">
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600 dark:bg-primary-900/30">
      <Icon :name="icon" :size="24" />
    </div>
    <h3 class="text-lg font-semibold text-ink dark:text-slate-100">{{ title }}</h3>
    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ description }}</p>
  </Card>
</template>

<script setup lang="ts">
import Card from '@/components/ui/Card.vue';
import Icon from '@/components/ui/Icon.vue';

defineProps<{
    icon: string;
    title: string;
    description: string;
}>();
</script>
