<template>
  <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">Title</span>
      <p class="truncate text-sm font-medium text-ink dark:text-slate-100">{{ title }}</p>
    </div>
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">Type</span>
      <Badge :label="mediaType ?? '—'" color="blue" />
    </div>
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">Platform</span>
      <Badge :label="platform ?? 'Direct'" color="slate" />
    </div>
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">Duration</span>
      <p class="text-sm text-ink dark:text-slate-200">{{ duration ? format(duration) : '—' }}</p>
    </div>
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">Resolution</span>
      <Badge :label="resolution ?? '—'" color="amber" />
    </div>
    <div class="flex flex-col gap-1">
      <span class="text-xs text-slate-500 dark:text-slate-400">File size</span>
      <p class="text-sm text-ink dark:text-slate-200">{{ fileSize ? humanSize(fileSize) : '—' }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import Badge from '@/components/ui/Badge.vue';

const props = withDefaults(
    defineProps<{
        title?: string | null;
        mediaType?: string | null;
        platform?: string | null;
        duration?: number | null;
        resolution?: string | null;
        fileSize?: number | null;
    }>(),
    {}
);

function format(seconds: number) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${String(s).padStart(2, '0')}`;
}

function humanSize(bytes: number) {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1048576) return `${(bytes / 1024).toFixed(1)} KB`;
    if (bytes < 1073741824) return `${(bytes / 1048576).toFixed(1)} MB`;
    return `${(bytes / 1073741824).toFixed(1)} GB`;
}
</script>
