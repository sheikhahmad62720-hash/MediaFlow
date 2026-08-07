<template>
  <div class="flex items-center gap-3 rounded-lg border border-slate-200 dark:border-slate-800 p-3">
    <img
      v-if="download.thumbnail_url"
      :src="download.thumbnail_url"
      :alt="download.title ?? 'Download'"
      class="h-12 w-20 shrink-0 rounded object-cover"
    />
    <div class="min-w-0 flex-1">
      <p class="truncate text-sm font-medium text-ink dark:text-slate-100">{{ download.title ?? 'Download' }}</p>
      <p class="text-xs text-slate-500 dark:text-slate-400">
        {{ download.platform?.name ?? 'Direct' }} · {{ formatDate(download.created_at) }}
      </p>
    </div>

    <Badge :label="download.status" :color="statusColor(download.status)" />

    <div class="flex items-center gap-2">
      <span class="text-xs text-slate-500 dark:text-slate-400">{{ download.file_size_human }}</span>
      <a
        v-if="download.status === 'completed' && download.file_url"
        :href="download.file_url"
        class="text-sm font-medium text-primary-600 hover:text-primary-700"
      >Get file</a>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Download } from '@/types';
import Badge from '@/components/ui/Badge.vue';

defineProps<{ download: Download }>();

function statusColor(status: string): 'green' | 'red' | 'amber' | 'slate' {
    const map: Record<string, 'green' | 'red' | 'amber' | 'slate'> = {
        completed: 'green',
        failed: 'red',
        processing: 'amber',
        queued: 'slate',
    };
    return map[status] ?? 'slate';
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>
