<template>
  <svg
    :viewBox="viewBox"
    :fill="fill"
    :style="{ width: sizePx, height: sizePx }"
    :class="className"
    role="img"
    aria-hidden="true"
    focusable="false"
  >
    <path :d="path" />
  </svg>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import icons from '@/components/ui/icons';

interface Props {
    name: string;
    size?: number | string;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    size: 20,
    class: '',
});

const sizePx = computed(() => {
    const n = typeof props.size === 'number' ? props.size : parseInt(String(props.size), 10);
    return `${Number.isNaN(n) ? 20 : n}px`;
});

const entry = computed(() => (icons as Record<string, IconDef>)[props.name] ?? (icons as Record<string, IconDef>)['alert-circle']);

const path = computed(() => entry.value.path);
const viewBox = computed(() => entry.value.viewBox ?? '0 0 24 24');
const fill = computed(() => entry.value.fill ?? 'currentColor');
const className = computed(() => ['inline-block', 'shrink-0', props.class]);

interface IconDef {
    path: string;
    viewBox?: string;
    fill?: string;
}
</script>
