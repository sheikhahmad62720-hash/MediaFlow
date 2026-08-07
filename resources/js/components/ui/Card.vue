<template>
  <div
    :class="classes"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        variant?: 'default' | 'bordered';
        class?: string;
        rounded?: boolean;
        hover?: boolean;
        padding?: boolean;
    }>(),
    { variant: 'default', rounded: true, hover: true, padding: true },
);

const classes = computed(() => [
    'bg-surface dark:bg-dark-card border border-slate-200 dark:border-slate-800 transition-shadow',
    props.variant === 'bordered' && 'shadow-sm',
    props.rounded && 'rounded-xl',
    props.hover && 'hover:shadow-card-hover',
    props.padding && 'p-6',
    props.class,
]);
</script>
