<template>
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Activity Logs</h1>
    </div>

    <Card>
      <div class="p-4 border-b border-slate-200 dark:border-slate-800">
        <div class="grid gap-3 sm:grid-cols-3">
          <Select v-model="filters.type" :options="typeOptions" placeholder="All types" />
          <Input v-model="filters.search" placeholder="Search description…" />
        </div>
      </div>

      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <div
          v-for="log in logs"
          :key="log.id"
          class="grid grid-cols-7 gap-3 px-4 py-3 text-sm"
        >
          <Badge :label="log.type" :color="typeColor(log.type)" />
          <div class="font-medium text-ink dark:text-slate-100">{{ log.description }}</div>
          <div class="text-slate-500 text-xs">{{ log.user?.name ?? '—' }}</div>
          <div class="text-slate-500 text-xs">{{ log.ip_address }}</div>
          <div class="text-slate-500 text-xs hidden sm:block">{{ formatDate(log.created_at) }}</div>
          <div class="text-slate-500 text-xs hidden lg:block">{{ log.user_agent }}</div>
        </div>

        <div v-if="!logs.length" class="py-8 text-center text-slate-500">No activity logs.</div>
      </div>

      <Pagination v-if="meta" :meta="meta" :query="paginationQuery" />
    </Card>
  </section>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import type { ActivityLog, PaginationMeta } from '@/types';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import Card from '@/components/ui/Card.vue';
import Badge from '@/components/ui/Badge.vue';
import Select from '@/components/ui/Select.vue';
import Input from '@/components/ui/Input.vue';
import Pagination from '@/components/ui/Pagination.vue';

const toast = useToastStore();
const route = useRoute();
const logs = ref<ActivityLog[]>([]);
const meta = ref<PaginationMeta | null>(null);
const filters = reactive({ type: '', search: '', page: 1, per_page: 25 });

const paginationQuery = computed(() =>
    Object.fromEntries(Object.entries(filters).map(([k, v]) => [k, String(v)])),
);

const typeOptions = [
    { label: 'Media analyzed', value: 'media.analyzed' },
    { label: 'Download created', value: 'download.created' },
    { label: 'Download started', value: 'download.started' },
    { label: 'Download completed', value: 'download.completed' },
    { label: 'Download failed', value: 'download.failed' },
    { label: 'Platform visited', value: 'platform.visited' },
];

watch(
    () => [filters.type, filters.search, route.query.page],
    () => {
        fetch();
    },
    { immediate: true }
);

async function fetch() {
    try {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => { if (v) params.set(k, String(v)); });
        params.set('page', String(filters.page));
        const { data } = await api.get('/admin/activity-logs', { params });
        logs.value = data.data;
        meta.value = data.meta;
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to load logs.');
    }
}

function typeColor(t: string): 'blue' | 'green' | 'red' | 'amber' | 'slate' {
    const map: Record<string, 'blue' | 'green' | 'red' | 'amber' | 'slate'> = {
        'media.analyzed': 'blue',
        'download.created': 'amber',
        'download.started': 'blue',
        'download.completed': 'green',
        'download.failed': 'red',
        'platform.visited': 'slate',
    };
    return map[t] ?? 'slate';
}

function formatDate(d: string) {
    return new Date(d).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}
</script>