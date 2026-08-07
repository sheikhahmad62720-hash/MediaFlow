<template>
  <section class="bg-canvas dark:bg-dark-canvas py-8">
    <div class="container-page">
            <div class="mx-auto max-w-3xl text-center">
                    <Badge label="Download anything, anywhere" color="primary" class="mx-auto" />
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-4xl">
                    Fast, private, and legal media downloads
                    </h2>
                    <p class="mt-5 text-lg text-slate-600 dark:text-slate-400">
                    Paste a supported link, retrieve metadata in seconds, then download the media file directly — no registration required for quick downloads.
                    </p>
            </div>

                <div class="mt-10">
                    <DownloadForm />
                </div>

            <div class="mt-8 flex justify-center">
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
