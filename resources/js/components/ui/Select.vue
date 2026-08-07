<template>
  <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
    {{ label }}
  </label>
  <select
    v-bind="$attrs"
    :value="modelValue"
    :class="classes"
    @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
  >
    <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
    <option v-for="option in options" :key="option.value" :value="option.value">
      {{ option.label }}
    </option>
  </select>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Option {
    label: string;
    value: string;
}

const props = withDefaults(
    defineProps<{
        label?: string;
        modelValue?: string;
        options: Option[];
        placeholder?: string;
        class?: string;
    }>(),
    { label: '', modelValue: '', placeholder: '' },
);

defineEmits<{ (e: 'update:modelValue', value: string): void }>();

const classes = computed(() => [
    'w-full rounded-lg border bg-surface dark:bg-dark-surface border-slate-300 dark:border-slate-700 text-ink',
    'px-4 py-2.5',
    props.class,
]);
</script>
