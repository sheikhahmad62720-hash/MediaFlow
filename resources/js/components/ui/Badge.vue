<template>
  <span
    :class="classes"
  >
    {{ label }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        label: string;
        variant?: 'solid' | 'subtle';
        color?: 'primary' | 'slate' | 'red' | 'green' | 'amber' | 'blue';
        class?: string;
    }>(),
    { variant: 'solid', color: 'slate' },
);

const colorMap = {
    primary: 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-200',
    slate: 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200',
    red: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
    green: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
    amber: 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
    blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
};

const classes = computed(() => [
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
    props.variant === 'solid' ? (colorMap[props.color] ?? colorMap.slate) : 'bg-transparent text-slate-500 dark:text-slate-400',
    props.class,
]);
</script>
