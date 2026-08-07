<template>
  <section class="py-16">
    <div class="container-page">
      <div class="mx-auto max-w-3xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">By the numbers</h2>
        <p class="mt-4 text-slate-600 dark:text-slate-400">Fast, legal, and trusted by everyday users.</p>
      </div>

      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <StatisticsCard value="12M+" label="Downloads served" />
        <StatisticsCard value="99.9%" label="Uptime" />
        <StatisticsCard value="120+" label="Supported platforms" />
        <StatisticsCard value="7d" label="Secure retention" />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import StatisticsCard from '@/components/sections/StatisticsCard.vue';
</script>
