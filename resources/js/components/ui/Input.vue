<template>
  <input
    v-bind="$attrs"
    :id="id"
    :type="type"
    :value="modelValue"
    :class="inputClasses"
    @input="onInput"
  />
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue';

const props = withDefaults(
    defineProps<{
        id?: string;
        type?: string;
        modelValue?: string;
        label?: string;
        hint?: string;
        error?: string;
        class?: string;
    }>(),
    { type: 'text', modelValue: '', label: '' },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

function onInput(event: Event) {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
}

const inputClasses = computed(() => [
    'w-full rounded-lg border bg-surface dark:bg-dark-surface border-slate-300 dark:border-slate-700 text-ink placeholder-slate-400 dark:placeholder-slate-500 transition-colors focus:ring-2 focus:ring-inset focus:ring-primary-500',
    'px-4 py-2.5',
    props.error ? 'border-red-500' : 'border-slate-300',
    props.class,
]);
</script>
