<template>
  <section class="container-page py-10">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-ink dark:text-slate-100">My Downloads</h1>
      <RouterLink to="/download" class="text-sm font-medium text-primary-600 hover:text-primary-700">New download</RouterLink>
    </div>

    <div v-if="loading" class="space-y-3">
      <SkeletonLoader v-for="n in 8" :key="n" height="64" />
    </div>

    <EmptyState
      v-else-if="!loading && downloads.length === 0"
      title="No downloads yet"
      description="Start a download — your completed files will appear here."
      icon="download"
    >
      <RouterLink
        to="/download"
        class="mt-2 inline-flex items-center gap-1 rounded-md bg-primary-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-primary-700"
      >
        <Icon name="arrow-right" :size="16" />
        Go to downloader
      </RouterLink>
    </EmptyState>

    <div v-else class="space-y-2">
      <DownloadResource
        v-for="download in downloads"
        :key="download.id"
        :download="download"
      />

      <Pagination v-if="meta" :meta="meta" :query="query" />
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import type { Download, PaginationMeta, PaginatedResponse } from '@/types';
import { useDownloadStore } from '@/stores/download';
import SkeletonLoader from '@/components/ui/SkeletonLoader.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';
import Icon from '@/components/ui/Icon.vue';
import DownloadResource from '@/components/media/DownloadResource.vue';

const route = useRoute();
const store = useDownloadStore();

const loading = ref(false);
const downloads = ref<Download[]>([]);
const meta = ref<PaginationMeta | null>(null);

const query = computed(() => {
    const q: Record<string, string> = {};
    if (route.query.status) q.status = route.query.status as string;
    if (route.query.format) q.format = route.query.format as string;
    if (route.query.search) q.search = route.query.search as string;
    return q;
});

watch(
    () => route.query,
    () => {
        fetch();
    },
    { immediate: true }
);

async function fetch() {
    loading.value = true;
    try {
        const params: Record<string, string> = {};
        Object.entries(route.query).forEach(([k, v]) => {
            if (typeof v === 'string') params[k] = v;
        });
        const res: PaginatedResponse<Download> = await store.fetchHistory(params);
        downloads.value = res.data;
        meta.value = res.meta ?? null;
    } finally {
        loading.value = false;
    }
}
</script>
