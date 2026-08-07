<template>
  <div class="space-y-2">
    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">
      Choose a format &amp; quality
    </label>
    <div class="flex flex-wrap gap-2">
      <button
        v-for="option in options"
        :key="optionKey(option)"
        type="button"
        @click="select(option)"
        :class="[
            'flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium transition-all',
            isSelected(option)
              ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30'
              : 'border-slate-200 dark:border-slate-800 hover:border-primary-300',
        ]"
      >
        <span class="font-semibold">{{ option.format.toUpperCase() }}</span>
        <span v-if="option.quality || option.resolution" class="text-slate-500">
          · {{ option.resolution ?? option.quality }}
        </span>
        <span v-if="option.file_size" class="text-slate-400">({{ humanSize(option.file_size) }})</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { DownloadOption } from '@/types';

const props = defineProps<{
    options: DownloadOption[];
    modelValue?: DownloadOption | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: DownloadOption | null): void;
    (e: 'select', value: DownloadOption): void;
}>();

const selected = computed(() => props.modelValue ?? props.options[0] ?? null);

function optionKey(option: DownloadOption): string {
    return `${option.format}-${option.resolution ?? option.quality ?? 'default'}`;
}

function isSelected(option: DownloadOption): boolean {
    return selected.value !== null && optionKey(option) === optionKey(selected.value);
}

function humanSize(bytes: number) {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1048576) return `${(bytes / 1024).toFixed(1)} KB`;
    if (bytes < 1073741824) return `${(bytes / 1048576).toFixed(1)} MB`;
    return `${(bytes / 1073741824).toFixed(1)} GB`;
}

function select(option: DownloadOption) {
    emit('update:modelValue', option);
    emit('select', option);
}
</script>
