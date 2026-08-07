<template>
  <div class="border-b border-slate-200 dark:border-slate-800">
    <button
      type="button"
      @click="open = !open"
      class="flex w-full items-center justify-between gap-3 py-4 text-left"
    >
      <span class="font-medium text-ink dark:text-slate-100">{{ title }}</span>
      <Icon :name="open ? 'chevron-up' : 'chevron-down'" :size="18" class="text-slate-500" />
    </button>
    <Transition name="slide">
      <div v-if="open" class="pb-3 text-slate-600 dark:text-slate-300">
        <p class="text-sm leading-relaxed"><slot /></p>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Icon from '@/components/ui/Icon.vue';

defineProps<{ title: string }>();
const open = ref(false);
</script>
