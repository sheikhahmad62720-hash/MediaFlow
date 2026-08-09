<template>
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Messages</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">Contact form submissions from the public site</p>
      </div>
      <Button variant="outline" size="sm" @click="fetch">
        <Icon name="cog" :size="16" class="mr-1" />
        Refresh
      </Button>
    </div>

    <LoadingState v-if="loading" text="Loading messages…" />

    <div v-else-if="messages.length" class="space-y-4">
      <Card
        v-for="message in messages"
        :key="message.id"
        :hover="false"
        :class="!message.is_read ? 'border-primary-300 dark:border-primary-700' : ''"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="flex min-w-0 items-start gap-4">
            <div
              class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-semibold"
              :class="
                message.is_read
                  ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'
                  : 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300'
              "
            >
              {{ initials(message.name) }}
            </div>

            <div class="min-w-0">
              <div class="flex flex-wrap items-center gap-2">
                <span class="font-semibold text-ink dark:text-slate-100">{{ message.name }}</span>
                <a
                  :href="`mailto:${message.email}`"
                  class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400"
                >
                  {{ message.email }}
                </a>
                <span
                  v-if="!message.is_read"
                  class="inline-flex items-center rounded-full bg-primary-600 px-2 py-0.5 text-xs font-medium text-white"
                >
                  New
                </span>
              </div>

              <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                {{ formatDate(message.created_at) }}
                <span v-if="message.ip_address" class="ml-2 hidden sm:inline">• {{ message.ip_address }}</span>
              </p>

              <p v-if="message.subject" class="mt-3 text-sm font-semibold text-ink dark:text-slate-100">
                {{ message.subject }}
              </p>
              <p class="mt-1 text-sm leading-relaxed whitespace-pre-wrap text-slate-600 dark:text-slate-300">
                {{ message.message }}
              </p>
            </div>
          </div>

          <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
            <Button
              v-if="!message.is_read"
              variant="outline"
              size="sm"
              @click="markRead(message)"
            >
              <Icon name="check" :size="14" class="mr-1" />
              Mark read
            </Button>
            <Button variant="danger" size="sm" @click="remove(message)">
              <Icon name="x" :size="14" class="mr-1" />
              Delete
            </Button>
          </div>
        </div>
      </Card>

      <Pagination v-if="meta" :meta="meta" :query="paginationQuery" />
    </div>

    <Card v-else>
      <EmptyState
        title="No messages yet"
        description="Contact form submissions from the public site will appear here."
        icon="mail"
      />
    </Card>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import type { ContactMessage, PaginationMeta } from '@/types';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import LoadingState from '@/components/ui/LoadingState.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';

const toast = useToastStore();
const route = useRoute();
const loading = ref(false);
const messages = ref<ContactMessage[]>([]);
const meta = ref<PaginationMeta | null>(null);

const paginationQuery = computed(() => ({ page: String(route.query.page ?? '1') }));

async function fetch() {
    loading.value = true;
    try {
        const { data } = await api.get('/admin/contact-messages', {
            params: { page: route.query.page ?? 1 },
        });
        messages.value = data.data;
        meta.value = data.meta;
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to load messages.');
    } finally {
        loading.value = false;
    }
}

async function markRead(message: ContactMessage) {
    try {
        await api.put(`/admin/contact-messages/${message.id}`);
        message.is_read = true;
        toast.success('Marked as read.');
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to update message.');
    }
}

async function remove(message: ContactMessage) {
    try {
        await api.delete(`/admin/contact-messages/${message.id}`);
        messages.value = messages.value.filter((m) => m.id !== message.id);
        toast.success('Message deleted.');
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to delete message.');
    }
}

function initials(name: string) {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase())
        .join('');
}

function formatDate(d: string) {
    return new Date(d).toLocaleString(undefined, {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

onMounted(fetch);
</script>
