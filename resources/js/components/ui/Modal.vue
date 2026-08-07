<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-50 flex items-center justify-center gap-2 p-4 text-center"
  >
    <div class="fixed inset-0 bg-black/50" @click="close" />
    <div
      class="relative z-10 w-full max-w-md rounded-xl bg-surface dark:bg-dark-card p-6 shadow-xl"
    >
      <button
        v-if="closable"
        type="button"
        class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
        @click="close"
      >
        <Icon name="x" :size="18" />
      </button>
      <div v-if="$slots.header" class="mb-4 text-left">
        <slot name="header" />
      </div>
      <div class="text-left">
        <slot />
      </div>
      <div v-if="$slots.footer" class="mt-6 flex justify-end gap-2">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        show: boolean;
        closable?: boolean;
    }>(),
    { closable: true },
);

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'close'): void;
}>();

const visible = computed(() => props.show);

function close() {
    emit('update:show', false);
    emit('close');
}
</script>
