<template>
  <textarea
    v-bind="$attrs"
    :id="id"
    :value="modelValue"
    :class="classes"
    :placeholder="placeholder"
    @input="onInput"
  />
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        id?: string;
        modelValue?: string;
        placeholder?: string;
        rows?: number;
        error?: string;
        class?: string;
    }>(),
    { modelValue: '', rows: 4, placeholder: '' },
);

const emit = defineEmits<{ (e: 'update:modelValue', value: string): void }>();

function onInput(event: Event) {
    emit('update:modelValue', (event.target as HTMLTextAreaElement).value);
}

const classes = computed(() => [
    'w-full rounded-lg border bg-surface dark:bg-dark-surface border-slate-300 dark:border-slate-700 text-ink placeholder-slate-400 dark:placeholder-slate-500 transition-colors',
    props.error ? 'border-red-500 focus:ring-red-500' : 'focus:ring-2 focus:ring-primary-500',
    'px-4 py-2.5 resize-y',
    props.class,
]);
</script>
