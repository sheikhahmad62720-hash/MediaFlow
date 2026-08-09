<template>
  <section>
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Admin Dashboard</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">Real-time overview of the platform</p>
      </div>
      <Button variant="outline" size="sm" @click="refresh">
        <Icon name="cog" :size="16" class="mr-1" />
        Refresh
      </Button>
    </div>

    <LoadingState v-if="loading" text="Loading dashboard…" />

    <div v-else class="space-y-6">
      <StatsGrid :stats="dashboard?.downloads ?? null" />

      <div class="grid gap-6 lg:grid-cols-3">
        <Card class="lg:col-span-2">
          <div class="p-5">
            <div class="mb-3 flex items-center justify-between">
              <h3 class="text-sm font-medium text-slate-700 dark:text-slate-200">Recent downloads</h3>
            </div>
            <RecentDownloads :downloads="dashboard?.recent ?? []" />
          </div>
        </Card>

        <Card class="p-5">
          <h3 class="text-sm font-medium text-slate-700 dark:text-slate-200">Top platforms</h3>
          <TopPlatforms :platforms="dashboard?.top_platforms ?? []" />
        </Card>
      </div>

      <Card>
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
          <div class="flex items-center gap-2">
            <h3 class="text-sm font-medium text-slate-700 dark:text-slate-200">Recent messages</h3>
            <span
              v-if="(dashboard?.messages?.unread_count ?? 0) > 0"
              class="inline-flex items-center rounded-full bg-primary-600 px-2 py-0.5 text-xs font-medium text-white"
            >
              {{ dashboard?.messages?.unread_count }} new
            </span>
          </div>
          <Button variant="ghost" size="sm" as="a" href="/admin/messages">
            View all
            <Icon name="arrow-right" :size="14" class="ml-1" />
          </Button>
        </div>
        <div class="p-5">
          <RecentMessages :messages="dashboard?.messages?.recent ?? []" />
        </div>
      </Card>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import type { ContactMessage, Download } from '@/types';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import LoadingState from '@/components/ui/LoadingState.vue';
import StatsGrid from '@/components/admin/StatsGrid.vue';
import RecentDownloads from '@/components/admin/RecentDownloads.vue';
import TopPlatforms from '@/components/admin/TopPlatforms.vue';
import RecentMessages from '@/components/admin/RecentMessages.vue';

interface DashboardData {
    downloads: Record<string, number>;
    recent: Download[];
    top_platforms: { slug: string; name: string; color: string; category: string; downloads: number; visits: number }[];
    messages: { unread_count: number; total: number; recent: ContactMessage[] };
}

const toast = useToastStore();
const loading = ref(false);
const dashboard = ref<DashboardData | null>(null);

async function refresh() {
    loading.value = true;
    try {
        const { data } = await api.get('/admin/dashboard');
        dashboard.value = data as DashboardData;
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to load dashboard.');
    } finally {
        loading.value = false;
    }
}

onMounted(refresh);
</script>
