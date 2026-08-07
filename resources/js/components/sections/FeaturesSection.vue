<template>
  <section class="py-16">
    <div class="container-page">
      <div class="mx-auto max-w-3xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">Everything you need to download media, safely</h2>
        <p class="mt-4 text-slate-600 dark:text-slate-400">
          Built with compliance and performance in mind — we never bypass platform protections.
        </p>
      </div>

      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <FeatureCard
          v-for="feature in features"
          :key="feature.title"
          :icon="feature.icon"
          :title="feature.title"
          :description="feature.description"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import FeatureCard from '@/components/sections/FeatureCard.vue';

const features = [
    { icon: 'download', title: 'Legal downloads only', description: 'We download only content that is publicly available or authorized by the platform.' },
    { icon: 'lock', title: 'Privacy focused', description: 'No tracking. Your URLs are processed on-demand and purged after the retention window.' },
    { icon: 'zap', title: 'Fast processing', description: 'Background queue engine keeps the UI fast and your files ready when you are.' },
    { icon: 'shield-check', title: 'Format variety', description: 'MP4, WEBM, MP3, M4A and image formats where supported.' },
    { icon: 'bolt', title: 'Metadata preview', description: 'Inspect thumbnails, duration, resolution and file size before downloading.' },
    { icon: 'clock', title: '7-day retention', description: 'Generated downloads are stored securely for seven days, then automatically removed.' },
];
</script>
