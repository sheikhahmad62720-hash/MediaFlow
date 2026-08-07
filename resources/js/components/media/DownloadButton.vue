<template>
  <Button
    as="button"
    :variant="variant"
    :loading="loading"
    :disabled="disabled || loading"
    :label="label"
    class="w-full sm:w-auto"
    @click="emit('start')"
  >
    <Icon name="download" :size="18" class="mr-2" v-if="!loading" />
    {{ label }}
  </Button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        loading?: boolean;
        disabled?: boolean;
        label?: string;
        variant?: 'primary' | 'secondary' | 'outline' | 'danger' | 'ghost';
    }>(),
    { loading: false, disabled: false, label: 'Download', variant: 'primary' }
);

const emit = defineEmits<{ (e: 'start'): void }>();
</script>
