<template>
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Downloads</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">All downloads with filtering and export</p>
      </div>
      <Button variant="outline" size="sm" @click="exportCsv">
        <Icon name="download" :size="16" class="mr-1" />
        Export CSV
      </Button>
    </div>

    <Card>
      <div class="p-4 border-b border-slate-200 dark:border-slate-800">
        <div class="grid gap-3 sm:grid-cols-4">
          <Select v-model="filters.status" :options="statusOptions" placeholder="All statuses" />
          <Select v-model="filters.format" :options="formatOptions" placeholder="All formats" />
          <Select v-model="filters.platform" :options="platformOptions" placeholder="All platforms" />
          <Input v-model="filters.search" placeholder="Search…" class="sm:col-span-2" />
        </div>
      </div>

      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <div
          v-for="download in downloads"
          :key="download.id"
          class="grid grid-cols-7 gap-3 px-4 py-3 text-sm"
        >
          <div class="flex items-center gap-2">
            <img v-if="download.thumbnail_url" :src="download.thumbnail_url" class="h-8 w-10 rounded object-cover" />
            <div>
              <p class="truncate font-medium text-ink dark:text-slate-100">{{ download.title }}</p>
              <p class="text-xs text-slate-500">{{ download.platform?.name }}</p>
            </div>
          </div>
          <div>{{ download.file_size_human }}</div>
          <Badge :label="download.status" :color="statusColor(download.status)" />
          <div>{{ download.format }}</div>
          <div class="text-slate-500">{{ formatDate(download.created_at) }}</div>
          <div class="flex items-center justify-end gap-2">
            <a v-if="download.file_url" :href="download.file_url" class="text-primary-600 hover:underline text-sm">Open</a>
          </div>
        </div>
      </div>

      <Pagination v-if="meta" :meta="meta" :query="paginationQuery" />
    </Card>
  </section>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import type { Download, PaginationMeta, PaginatedResponse } from '@/types';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import Card from '@/components/ui/Card.vue';
import Badge from '@/components/ui/Badge.vue';
import Select from '@/components/ui/Select.vue';
import Input from '@/components/ui/Input.vue';
import Button from '@/components/ui/Button.vue';
import Pagination from '@/components/ui/Pagination.vue';
import Icon from '@/components/ui/Icon.vue';

const toast = useToastStore();
const loading = ref(false);
const downloads = ref<Download[]>([]);
const meta = ref<PaginationMeta | null>(null);
const filters = reactive({ status: '', format: '', platform: '', search: '', page: 1, per_page: 25 });

const paginationQuery = computed(() =>
    Object.fromEntries(Object.entries(filters).map(([k, v]) => [k, String(v)])),
);

const statusOptions = [
    { label: 'Completed', value: 'completed' },
    { label: 'Queued', value: 'queued' },
    { label: 'Processing', value: 'processing' },
    { label: 'Failed', value: 'failed' },
];
const formatOptions = [
    { label: 'MP4', value: 'mp4' },
    { label: 'WEBM', value: 'webm' },
    { label: 'MP3', value: 'mp3' },
    { label: 'M4A', value: 'm4a' },
    { label: 'JPG', value: 'jpg' },
    { label: 'PNG', value: 'png' },
    { label: 'WEBP', value: 'webp' },
];
const platformOptions: { label: string; value: string }[] = [];

watch(
    () => [filters.status, filters.format, filters.platform, filters.search],
    () => {
        fetch();
    },
    { immediate: true }
);

async function fetch() {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => {
            if (v) params.set(k, String(v));
        });
        params.set('page', String(filters.page));

        const { data } = await api.get('/admin/downloads', { params });
        downloads.value = data.data;
        meta.value = data.meta;
        if (platformOptions.length === 0 && data.data.length) {
            const uniq = [...new Set(data.data.map((d: Download) => d.platform?.slug ?? ''))] as string[];
            uniq.forEach((slug) => {
                if (!slug) return;
                const p = data.data.find((d: Download) => d.platform?.slug === slug);
                const platform = p?.platform;
                if (platform) platformOptions.push({ label: platform.name, value: slug });
            });
        }
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to load downloads.');
    } finally {
        loading.value = false;
    }
}

function statusColor(s: string): 'green' | 'red' | 'amber' | 'slate' {
    const map: Record<string, 'green' | 'red' | 'amber' | 'slate'> = {
        completed: 'green',
        failed: 'red',
        processing: 'amber',
        queued: 'slate',
    };
    return map[s] ?? 'slate';
}

function formatDate(d: string) {
    return new Date(d).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

function exportCsv() {
    const rows = [
        ['ID', 'Title', 'Platform', 'Status', 'Format', 'Size', 'Created'],
        ...downloads.value.map((d: Download) => [d.id, d.title ?? '', d.platform?.name ?? '', d.status, d.format, d.file_size_human ?? '', d.created_at]),
    ];
    const blob = new Blob([rows.map((r) => r.map((c) => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n')], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `downloads-${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}
</script>