<template>
  <div>
    <HeroSection />
    <FeaturesSection />
    <StatisticsSection />
    <FaqSection />
    <TestimonialsSection />

    <section class="container-page py-12">
      <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-6">
        <h3 class="text-center text-lg font-medium text-ink dark:text-slate-100">Ready to download?</h3>
        <p class="mt-1 text-center text-sm text-slate-600 dark:text-slate-400">
          Try the downloader now — no signup required.
        </p>
        <div class="mt-4">
          <RouterLink
            to="/download"
            class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-700"
          >
            Go to downloader
            <Icon name="arrow-right" :size="16" class="ml-1" />
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import HeroSection from '@/components/sections/HeroSection.vue';
import FeaturesSection from '@/components/sections/FeaturesSection.vue';
import StatisticsSection from '@/components/sections/StatisticsSection.vue';
import FaqSection from '@/components/sections/FaqSection.vue';
import TestimonialsSection from '@/components/sections/TestimonialsSection.vue';
import Icon from '@/components/ui/Icon.vue';
</script>
