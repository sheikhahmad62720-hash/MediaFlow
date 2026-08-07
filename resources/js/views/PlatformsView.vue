<template>
  <section class="container-page py-10">
    <div class="mb-8 text-center">
      <h1 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">Supported Platforms</h1>
      <p class="mt-2 text-slate-600 dark:text-slate-400">
        We currently support downloading from the following platforms.
      </p>
    </div>

    <LoadingState v-if="loading" text="Loading platforms…" />

    <EmptyState
      v-else-if="!loading && platforms.length === 0"
      title="No platforms available"
      description="Check back soon — we are adding support for more platforms."
    />

    <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <PlatformCard
        v-for="platform in platforms"
        :key="platform.id"
        :platform="platform"
      />
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { usePlatformStore } from '@/stores/platform';
import LoadingState from '@/components/ui/LoadingState.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import PlatformCard from '@/components/sections/PlatformCard.vue';

const store = usePlatformStore();
const platforms = computed(() => store.platforms);
const loading = computed(() => store.loading);

onMounted(() => store.fetch());
</script>
