<template>
  <button
    type="button"
    :aria-label="`Switch to ${toggleLabel} mode`"
    :title="toggleLabel + ' mode'"
    class="focus-ring relative inline-flex h-9 w-17 items-center rounded-full transition-colors"
    @click="toggle"
  >
    <span
      :class="['absolute inset-0 flex items-center justify-between px-1.5 text-white', {
        'justify-end': isDark,
      }]"
    >
      <Icon v-if="!isDark" name="sun" :size="18" />
      <Icon v-if="isDark" name="moon" :size="18" />
    </span>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme';
import Icon from '@/components/ui/Icon.vue';

const store = useThemeStore();

const isDark = computed(() => store.isDark);

const toggleLabel = computed(() => (isDark.value ? 'Light' : 'Dark'));

function toggle() {
    const next = isDark.value ? 'light' : 'dark';
    store.set(next);
}
</script>
