<template>
  <div class="flow-root">
    <div class="space-y-3">
      <div
        v-for="message in messages"
        :key="message.id"
        class="flex items-start gap-3"
      >
        <div
          class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
          :class="
            message.is_read
              ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'
              : 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300'
          "
        >
          {{ initials(message.name) }}
        </div>
        <div class="min-w-0 flex-1">
          <div class="flex items-center justify-between gap-2">
            <p class="truncate text-sm font-medium text-ink dark:text-slate-100">
              {{ message.name }}
            </p>
            <span class="shrink-0 text-xs text-slate-500 dark:text-slate-400">
              {{ formatDate(message.created_at) }}
            </span>
          </div>
          <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ message.email }}</p>
          <p class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-300">
            {{ message.subject || message.message }}
          </p>
        </div>
        <span
          v-if="!message.is_read"
          class="mt-1 h-2 w-2 shrink-0 rounded-full bg-primary-500"
          title="Unread"
        />
      </div>

      <EmptyState
        v-if="!messages.length"
        title="No messages yet"
        description="Contact form submissions will appear here."
        icon="mail"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { ContactMessage } from '@/types';
import EmptyState from '@/components/ui/EmptyState.vue';

defineProps<{ messages: ContactMessage[] }>();

function initials(name: string) {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase())
        .join('');
}

function formatDate(d: string) {
    return new Date(d).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}
</script>
