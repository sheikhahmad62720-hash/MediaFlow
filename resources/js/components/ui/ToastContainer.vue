<template>
  <div class="fixed inset-x-4 bottom-6 z-[100] flex flex-col items-end gap-3">
    <TransitionGroup name="tween-opacity" tag="div">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="toastClass(toast.type)"
        class="relative w-full max-w-sm rounded-lg p-4 shadow-lg"
      >
        <div class="flex items-start gap-3">
          <Icon :name="toastTypeIcon(toast.type)" :size="20" class="mt-0.5 shrink-0" />
          <p class="flex-1 text-sm">{{ toast.message }}</p>
          <button
            type="button"
            class="shrink-0 text-slate-300 hover:text-slate-100"
            @click="remove(toast.id)"
          >
            <Icon name="x" :size="16" />
          </button>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useToastStore } from '@/stores/toast';
import Icon from '@/components/ui/Icon.vue';

const store = useToastStore();

const toasts = computed(() => store.toasts);

function remove(id: number) {
    store.remove(id);
}

function toastClass(type: string) {
    return {
        success: 'bg-green-600 text-white',
        error: 'bg-red-600 text-white',
        info: 'bg-slate-800 text-white',
    }[type] ?? 'bg-slate-800 text-white';
}

function toastTypeIcon(type: string) {
    return { success: 'check', error: 'alert-circle', info: 'info' }[type] ?? 'info';
}
</script>
