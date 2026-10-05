<template>
  <div class="space-y-3">
    <div v-if="loading" class="space-y-3">
      <SkeletonLoader v-for="n in 5" :key="n" height="60" />
    </div>

    <EmptyState
      v-else-if="!loading && downloads.length === 0"
      title="No downloads yet"
      description="Your download history will appear here."
      icon="download"
    />

    <div v-else class="space-y-2">
      <div
        v-for="item in downloads"
        :key="item.id"
        class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 transition-colors hover:border-primary-200 hover:bg-primary-50/40 dark:border-slate-800 dark:hover:border-primary-900/50 dark:hover:bg-primary-900/10"
      >
        <img
          v-if="item.thumbnail_url"
          :src="item.thumbnail_url"
          :alt="item.title ?? 'Download'"
          class="h-12 w-20 shrink-0 rounded object-cover"
        />
        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-medium text-ink dark:text-slate-100">{{ item.title ?? 'Download' }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ formatDate(item.created_at) }} · {{ item.platform?.name ?? 'Direct' }}
          </p>
        </div>

        <Badge :label="item.status" :color="statusColor(item.status)" />

        <a
          v-if="item.status === 'completed' && item.file_url"
          :href="item.file_url"
          class="text-sm font-medium text-primary-600 hover:text-primary-700"
        >
          Get file
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import type { Download } from '@/types';
import Badge from '@/components/ui/Badge.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import SkeletonLoader from '@/components/ui/SkeletonLoader.vue';
import { useDownloadStore } from '@/stores/download';
import { useAuthStore } from '@/stores/auth';

const downloadStore = useDownloadStore();
const auth = useAuthStore();
const downloading = ref(false);

const downloads = ref<Download[]>([]);

const loading = computed(() => downloading.value);

onMounted(async () => {
    if (!auth.isAuthenticated) {
        return;
    }

    fetching();
});

async function fetching() {
    downloading.value = true;
    try {
        const res = await downloadStore.fetchHistory();
        downloads.value = res.data;
    } finally {
        downloading.value = false;
    }
}

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
    return new Date(date).toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
}
</script>
