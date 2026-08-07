<template>
  <section class="container-page py-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">Universal Media Downloader</h1>
      <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-400">
        Paste a link from YouTube, Vimeo, SoundCloud, Spotify or any directly hosted media file and we’ll fetch the metadata and start a download for you.
      </p>
    </div>

    <div class="grid gap-8 xl:grid-cols-3 xl:items-start">
      <div class="xl:col-span-2 space-y-6">
        <DownloadForm />

        <Card v-if="download.current" variant="bordered" class="p-4">
          <ProgressTracker :download="download.current" />
        </Card>
      </div>

      <div class="xl:col-span-1">
        <DownloadHistory />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import Card from '@/components/ui/Card.vue';
import DownloadForm from '@/components/media/DownloadForm.vue';
import ProgressTracker from '@/components/media/ProgressTracker.vue';
import DownloadHistory from '@/components/media/DownloadHistory.vue';
import { useDownloadStore } from '@/stores/download';

const download = useDownloadStore();
</script>
