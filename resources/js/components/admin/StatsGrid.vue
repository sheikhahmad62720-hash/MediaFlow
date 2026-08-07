<template>
  <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
    <StatCard label="Total" :value="stats?.total ?? 0" color="slate" />
    <StatCard label="Completed" :value="stats?.completed ?? 0" color="green" />
    <StatCard label="Queued" :value="stats?.queued ?? 0" color="slate" />
    <StatCard label="Processing" :value="stats?.processing ?? 0" color="amber" />
    <StatCard label="Failed" :value="stats?.failed ?? 0" color="red" />
    <StatCard label="Today" :value="stats?.today ?? 0" color="blue" />
  </div>
</template>

<script setup lang="ts">
import StatCard from '@/components/admin/StatCard.vue';

defineProps<{
    stats: Record<string, number> | null;
}>();
</script>
