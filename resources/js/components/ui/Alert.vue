<template>
  <div
    :class="[
      'rounded-lg p-4 text-sm',
      colorClass,
      borderClass,
    ]"
  >
    <div class="flex items-start gap-3">
      <Icon :name="icon" :size="20" class="mt-0.5 shrink-0" />
      <p class="flex-1">{{ message }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        type?: 'info' | 'success' | 'warning' | 'error';
        message: string;
        icon?: string;
    }>(),
    { type: 'info', icon: 'alert-circle' },
);

const colorClass = computed(() => {
    return {
        info: 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200',
        success: 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-200',
        warning: 'bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-200',
        error: 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200',
    }[props.type];
});

const borderClass = computed(() => {
    return {
        info: 'border border-blue-200 dark:border-blue-900/30',
        success: 'border border-green-200 dark:border-green-900/30',
        warning: 'border border-amber-200 dark:border-amber-900/30',
        error: 'border border-red-200 dark:border-red-900/30',
    }[props.type];
});
</script>
