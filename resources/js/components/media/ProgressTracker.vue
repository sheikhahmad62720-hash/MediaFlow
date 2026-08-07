<template>
  <div v-if="download" class="rounded-lg border border-slate-200 dark:border-slate-800 p-4">
    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <div :class="statusDot">
          <LoadingSpinner v-if="inProgress" :loading="true" :size="16" />
          <Icon v-else :name="statusIcon" :size="16" />
        </div>
        <div class="min-w-0">
          <p class="text-sm font-medium text-ink dark:text-slate-100 truncate">{{ download.title ?? download.source_url }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">
            {{ displayStatus }}
            <span v-if="download.file_size_human" class="mx-1">·</span>
            <span v-if="download.file_size_human">{{ download.file_size_human }}</span>
          </p>
        </div>
      </div>

      <div v-if="download.status === 'completed'" class="flex items-center gap-2">
        <a
          v-if="download.file_url"
          :href="download.file_url"
          class="text-sm font-medium text-primary-600 hover:text-primary-700"
        >
          Open file
        </a>
        <Badge :label="download.format?.toUpperCase() ?? ''" color="blue" />
      </div>
      <Button v-else-if="download.status === 'failed'" variant="secondary" size="xs">
        Retry
      </Button>
    </div>

    <div v-if="inProgress" class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
      <div class="h-full w-3/4 animate-pulse rounded-full bg-primary-500" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Download } from '@/types';
import Badge from '@/components/ui/Badge.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        download: Download | null;
    }>(),
    { download: null },
);

const inProgress = computed(() => props.download && ['queued', 'processing'].includes(props.download.status));

const displayStatus = computed(() => {
    const s = props.download?.status;
    return {
        completed: 'Completed',
        failed: 'Failed',
        queued: 'Queued… starting',
        processing: 'Downloading…',
    }[s ?? 'queued'] ?? s;
});

const statusIcon = computed(() => {
    const s = props.download?.status;
    return {
        completed: 'checkcircle',
        failed: 'alert-circle',
        queued: 'clock',
        processing: 'clock',
    }[s ?? 'queued'] ?? 'clock';
});

const statusDot = computed(() => {
    const base = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full';
    const s = props.download?.status;
    return {
        completed: `${base} bg-green-100 text-green-600 dark:bg-green-900/30`,
        failed: `${base} bg-red-100 text-red-600 dark:bg-red-900/30`,
        queued: `${base} bg-slate-200 dark:bg-slate-700 text-slate-600`,
        processing: `${base} bg-amber-100 text-amber-600 dark:bg-amber-900/30`,
    }[s ?? 'queued'] ?? `${base} bg-slate-200 text-slate-600`;
});
</script>
