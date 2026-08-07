<template>
  <p class="mt-1 flex items-center gap-1 text-sm text-red-600">
    <Icon name="alert-circle" :size="16" />
    {{ message }}
  </p>
</template>

<script setup lang="ts">
import Icon from '@/components/ui/Icon.vue';

defineProps<{ message?: string }>();
</script>
