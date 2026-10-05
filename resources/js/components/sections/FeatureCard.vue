<template>
  <Card
    variant="bordered"
    class="group p-6 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover"
  >
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-lg shadow-primary-500/25 transition-transform duration-300 group-hover:scale-110 dark:from-primary-600 dark:to-primary-800">
      <Icon :name="icon" :size="24" />
    </div>
    <h3 class="text-lg font-semibold text-ink dark:text-slate-100">{{ title }}</h3>
    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ description }}</p>
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
