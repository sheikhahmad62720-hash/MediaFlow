<template>
  <component
    :is="tag"
    v-bind="$attrs"
    :type="type"
    :href="href"
    :disabled="disabled || loading"
    :class="classes"
    @click="onClick"
  >
    <LoadingSpinner v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" />
    <span v-if="label && !$slots.default">{{ label }}</span>
    <slot />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';

const props = withDefaults(
    defineProps<{
        as?: 'button' | 'a' | 'div';
        variant?: 'primary' | 'secondary' | 'ghost' | 'danger' | 'outline';
        size?: 'xs' | 'sm' | 'md' | 'lg';
        type?: 'button' | 'submit' | 'reset';
        disabled?: boolean;
        loading?: boolean;
        href?: string;
        label?: string;
        class?: string;
    }>(),
    {
        as: 'button',
        variant: 'primary',
        size: 'md',
        type: 'button',
        disabled: false,
        loading: false,
        label: '',
    },
);

const emit = defineEmits<{ (e: 'click', ev: MouseEvent): void }>();

const tag = computed(() => (props.as === 'a' ? 'a' : props.as === 'div' ? 'div' : 'button'));

const variantClasses = computed(() => {
    const v = {
        primary: 'bg-primary-600 hover:bg-primary-700 text-white shadow hover:shadow-md focus:ring-primary-500',
        secondary: 'bg-slate-100 hover:bg-slate-200 text-slate-900 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100 focus:ring-slate-500',
        ghost: 'hover:bg-slate-100 text-slate-700 dark:hover:bg-slate-800 dark:text-slate-300 focus:ring-slate-500',
        danger: 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        outline: 'border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:text-slate-200 focus:ring-slate-500',
    };
    return v[props.variant];
});

const sizeClasses = computed(() => {
    const s = {
        xs: 'px-3 py-1.5 text-xs',
        sm: 'px-4 py-2 text-sm',
        md: 'px-5 py-2.5 text-sm',
        lg: 'px-6 py-3',
    };
    return s[props.size];
});

const classes = computed(() => [
    'focus-ring inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200',
    'disabled:opacity-60 disabled:cursor-not-allowed',
    variantClasses.value,
    sizeClasses.value,
    props.class,
]);

function onClick(event: MouseEvent) {
    if (props.disabled || props.loading) {
        return;
    }
    emit('click', event);
}
</script>
