<template>
  <Card
    class="border transition group-hover:shadow-card-hover"
  >
    <div class="p-5">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="block h-3 w-3 rounded-full" :style="{ backgroundColor: platform.color }"></span>
          <h3 class="text-lg font-semibold text-ink dark:text-slate-100">{{ platform.name }}</h3>
        </div>
        <Badge :label="platform.category" :color="categoryColor(platform.category)" />
      </div>

      <p v-if="platform.description" class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">
        {{ platform.description }}
      </p>

      <div v-if="platform.formats?.length" class="mt-3 flex flex-wrap gap-1">
        <span
          v-for="fmt in platform.formats"
          :key="fmt"
          class="text-xs text-slate-500 dark:text-slate-400"
        >{{ fmt.toUpperCase() }}</span>
      </div>

      <div v-if="platform.domain" class="mt-3 flex items-center gap-1.5">
        <Icon name="link" :size="14" class="text-slate-400" />
        <span class="truncate text-xs text-slate-500 dark:text-slate-400">{{ platform.domain }}</span>
      </div>

      <div class="mt-4 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <span>{{ platform.download_count }} downloads</span>
        <span>{{ platform.visit_count }} visits</span>
      </div>
    </div>
  </Card>
</template>

<script setup lang="ts">
import type { Platform } from '@/types';
import Card from '@/components/ui/Card.vue';
import Badge from '@/components/ui/Badge.vue';
import Icon from '@/components/ui/Icon.vue';

const props = defineProps<{ platform: Platform }>();

const categoryColor = (category: string): 'blue' | 'green' | 'amber' | 'slate' => {
    const map: Record<string, 'blue' | 'green' | 'amber' | 'slate'> = {
        video: 'blue',
        music: 'green',
        social: 'blue',
        image: 'amber',
        direct: 'slate',
    };
    return map[category] ?? 'slate';
};
</script>
