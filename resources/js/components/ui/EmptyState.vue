<template>
  <div class="flex flex-col items-center gap-4 py-12 text-center">
    <div
      class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
    >
      <Icon :name="icon" :size="32" class="text-slate-400 dark:text-slate-500" />
    </div>
    <h3 class="text-lg font-medium text-slate-700 dark:text-slate-200">{{ title }}</h3>
    <p v-if="description" class="max-w-sm text-sm text-slate-500 dark:text-slate-400">
      {{ description }}
    </p>
    <Button v-if="$slots.default" variant="primary" size="sm">
      <slot />
    </Button>
  </div>
</template>

<script setup lang="ts">
import Icon from '@/components/ui/Icon.vue';
import Button from '@/components/ui/Button.vue';

const props = withDefaults(
    defineProps<{
        title?: string;
        description?: string;
        icon?: string;
    }>(),
    { title: 'Nothing here yet', description: '', icon: 'alert-circle' },
);
</script>
