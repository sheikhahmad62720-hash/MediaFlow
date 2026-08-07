<template>
  <div class="relative flex aspect-video w-full items-center justify-center overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800">
    <img
      v-if="thumbnail"
      :src="thumbnail"
      :alt="title ?? 'Media'"
      class="h-full w-full object-cover"
      loading="lazy"
    />
    <div v-else class="flex h-full w-full items-center justify-center">
      <Icon name="media-video" :size="48" class="text-slate-300 dark:text-slate-600" />
    </div>

    <div v-if="duration" class="absolute bottom-2 right-2 rounded-md bg-black/60 px-2 py-0.5 text-xs text-white">
      {{ formatDuration(duration) }}
    </div>
    <div v-if="type === 'video'" class="absolute inset-0 flex items-center justify-center">
      <div class="rounded-full bg-white/80 p-2 shadow">
        <Icon name="play-pause" :size="24" class="text-primary-600" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        thumbnail?: string | null;
        title?: string | null;
        duration?: number | null;
        type?: string;
    }>(),
    { thumbnail: null, title: 'Media', duration: null, type: 'video' }
);

function formatDuration(seconds: number) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${String(s).padStart(2, '0')}`;
}
</script>
