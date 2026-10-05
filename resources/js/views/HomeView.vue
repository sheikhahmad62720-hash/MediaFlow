<template>
  <div>
    <HeroSection />
    <FeaturesSection />
    <StatisticsSection />
    <FaqSection />
    <TestimonialsSection />

    <section class="container-page py-12">
      <div
        class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 via-primary-600 to-primary-800 px-6 py-10 text-center shadow-xl shadow-primary-600/25 sm:px-10"
      >
        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

        <div class="relative">
          <h3 class="text-2xl font-bold tracking-tight text-white">Ready to download?</h3>
          <p class="mx-auto mt-2 max-w-md text-sm text-primary-100 sm:text-base">
            Try the downloader now — no signup required.
          </p>
          <div class="mt-6">
            <RouterLink
              to="/download"
              class="focus-ring inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-primary-700 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:bg-primary-50 hover:shadow-xl"
            >
              Go to downloader
              <Icon name="arrow-right" :size="16" />
            </RouterLink>
          </div>
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
