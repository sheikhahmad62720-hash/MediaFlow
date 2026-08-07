<template>
  <svg
    v-if="isProgress"
    class="-ml-1 mr-2 h-4 w-4 animate-spin"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    aria-hidden="true"
  >
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
    <path
      class="opacity-75"
      fill="currentColor"
      d="M4 12a8 8 0 018-8v8H4z"
    />
  </svg>
  <svg
    v-else
    :class="className"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    aria-hidden="true"
  >
    <path :d="path" stroke="currentColor" stroke-width="1.5" />
  </svg>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        name?: string;
        size?: number;
        class?: string;
        loading?: boolean;
    }>(),
    { name: 'spinner', size: 20 },
);

const isProgress = computed(() => props.loading);
const path = computed(() => 'M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M4 12h4m12 0h4');
const className = computed(() => [
    'inline-block',
    'text-slate-500',
    { 'animate-spin': props.loading },
    props.class,
]);
</script>
