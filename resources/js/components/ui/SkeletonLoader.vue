<template>
  <div
    v-if="height === 'full' || !rows"
    class="animate-pulse rounded-lg bg-slate-200 dark:bg-slate-700"
    :style="{ height: height ?? '1rem', width: width ?? '100%' }"
  />

  <div
    v-else
    class="space-y-2 animate-pulse"
    :style="{ width }"
  >
    <div
      v-for="n in rows"
      :key="n"
      class="h-4 rounded bg-slate-200 dark:bg-slate-700"
      :style="{ width: rowWidth(n) }"
    />
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        width?: string;
        height?: string;
        rows?: number;
        class?: string;
    }>(),
    {},
);

function rowWidth(n: number) {
    if (n === props.rows) {
        return '60%';
    }
    return n === props.rows! - 1 ? '80%' : '100%';
}
</script>
