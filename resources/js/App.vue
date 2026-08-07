<template>
  <div class="min-h-screen bg-surface dark:bg-dark-surface text-ink transition-colors">
    <router-view />

    <ToastContainer />
  </div>
</template>

<script setup lang="ts">
import ToastContainer from '@/components/ui/ToastContainer.vue';
</script>
