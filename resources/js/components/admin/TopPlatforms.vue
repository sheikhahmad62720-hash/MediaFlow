<template>
  <ul class="divide-y divide-slate-200 dark:divide-slate-800">
    <li
      v-for="platform in platforms"
      :key="platform.slug"
      class="flex items-center gap-3 py-2"
    >
      <span class="block h-3 w-3 shrink-0 rounded-full" :style="{ backgroundColor: platform.color }" />
      <div class="min-w-0 flex-1">
        <p class="text-sm font-medium text-ink dark:text-slate-100">{{ platform.name }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ platform.downloads }} downloads</p>
      </div>
      <Badge :label="platform.category" :color="categoryColor(platform.category)" />
    </li>

    <li v-if="!platforms.length" class="py-4 text-center text-sm text-slate-500">
      No platform data yet.
    </li>
  </ul>
</template>

<script setup lang="ts">
interface PlatformStat {
    slug: string;
    name: string;
    color: string;
    category: string;
    downloads: number;
    visits: number;
}

defineProps<{ platforms: PlatformStat[] }>();

function categoryColor(category: string): 'blue' | 'green' | 'amber' | 'slate' {
    const map: Record<string, 'blue' | 'green' | 'amber' | 'slate'> = {
        video: 'blue',
        music: 'green',
        social: 'blue',
        image: 'amber',
        direct: 'slate',
    };
    return map[category] ?? 'slate';
}
</script>
