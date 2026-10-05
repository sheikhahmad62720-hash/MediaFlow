<template>
  <section class="relative overflow-hidden bg-canvas dark:bg-dark-canvas">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
      <div class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-primary-400/25 blur-3xl dark:bg-primary-600/20"></div>
      <div class="absolute -right-16 top-40 h-56 w-56 rounded-full bg-sky-400/20 blur-3xl dark:bg-sky-500/10"></div>
      <div class="absolute -left-16 top-56 h-56 w-56 rounded-full bg-violet-400/20 blur-3xl dark:bg-violet-500/10"></div>
      <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-surface to-transparent dark:from-dark-surface"></div>
    </div>

    <div class="container-page relative py-16 sm:py-20">
            <div class="mx-auto max-w-3xl text-center">
                    <Badge label="Download anything, anywhere" color="primary" class="mx-auto animate-fade-up" />
                    <h2 class="mt-5 animate-fade-up text-4xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-5xl" style="animation-delay: 0.05s">
                    Fast, private, and <span class="text-gradient">legal media downloads</span>
                    </h2>
                    <p class="mx-auto mt-5 max-w-2xl animate-fade-up text-lg leading-relaxed text-slate-600 dark:text-slate-400" style="animation-delay: 0.1s">
                    Paste a supported link, retrieve metadata in seconds, then download the media file directly — no registration required for quick downloads.
                    </p>
            </div>

                <div class="mx-auto mt-10 max-w-3xl animate-fade-up" style="animation-delay: 0.15s">
                    <DownloadForm />
                </div>

            <div class="mt-8 flex justify-center animate-fade-up" style="animation-delay: 0.2s">
                <RouterLink
                to="/platforms"
                class="text-sm font-medium text-primary-600 hover:text-primary-700"
                >
                See all supported platforms
                <Icon name="arrow-right" :size="16" class="ml-1 inline" />
                </RouterLink>
            </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import Badge from '@/components/ui/Badge.vue';
import Icon from '@/components/ui/Icon.vue';
import DownloadForm from '@/components/media/DownloadForm.vue';
</script>
