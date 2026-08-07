<template>
  <div class="flow-root">
    <div class="space-y-2">
      <div
        v-for="download in downloads"
        :key="download.id"
        class="flex items-center gap-3"
      >
        <img
          v-if="download.thumbnail_url"
          :src="download.thumbnail_url"
          :alt="download.title ?? download.source_url"
          class="hidden h-10 w-16 shrink-0 rounded object-cover sm:block"
        />
        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-medium text-ink dark:text-slate-100">{{ download.title ?? download.source_url }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ download.platform?.name ?? 'Direct' }}</p>
        </div>
        <Badge :label="download.status" :color="statusColor(download.status)" />
      </div>

      <EmptyState
        v-if="!downloads.length"
        title="No recent downloads"
        description="Downloads will appear here once available."
        icon="download"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Download } from '@/types';
import Badge from '@/components/ui/Badge.vue';
import EmptyState from '@/components/ui/EmptyState.vue';

defineProps<{ downloads: Download[] }>();

function statusColor(status: string): 'green' | 'red' | 'amber' | 'slate' {
    const map: Record<string, 'green' | 'red' | 'amber' | 'slate'> = {
        completed: 'green',
        failed: 'red',
        processing: 'amber',
        queued: 'slate',
    };
    return map[status] ?? 'slate';
}
</script>
