<template>
  <Card variant="bordered" class="p-6">
    <div class="space-y-5">
      <!-- 1. URL input -->
      <UrlInput
        v-model="url"
        :loading="analyzing"
        :error="error ?? undefined"
        placeholder="    Paste a media URL from YouTube, Vimeo, SoundCloud, or any direct link…"
        @paste="onPaste"
      />

      <!-- 2. Analyze action -->
      <div class="flex justify-end">
        <Button
          label="Analyze"
          :loading="analyzing"
          :disabled="!url || analyzing"
          @click="analyze"
        />
      </div>

      <!-- 3. Loading state -->
      <LoadingState v-if="analyzing" name="spinner" :size="32" text="Analyzing media…" />

      <!-- 4. Preview + info -->
      <div v-else-if="metadata" class="grid gap-6 md:grid-cols-3">
        <div class="md:col-span-1">
          <MediaPreview
            :thumbnail="metadata.thumbnail_url"
            :title="metadata.title"
            :duration="metadata.duration"
            :type="metadata.media_type"
          />
        </div>

        <div class="md:col-span-2 space-y-5">
          <MediaInfo
            :title="metadata.title"
            :media-type="metadata.media_type"
            :platform="metadata.platform"
            :duration="metadata.duration"
            :resolution="metadata.resolution"
            :file-size="primaryFileSize"
          />

          <DownloadOptions v-model="selectedOption" :options="metadata.formats" />

          <div class="flex items-center justify-between pt-2">
            <Button
              label="Download"
              :loading="downloading"
              :disabled="downloading"
              @click="download"
            />

            <Alert v-if="metadata.raw.note" type="info" :message="String(metadata.raw.note)" />
          </div>
        </div>
      </div>

      <EmptyState
        v-else
        title="Enter a media URL to get started"
        description="Paste a supported link and click Analyze to see the available formats."
        icon="link"
      />
    </div>
  </Card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';
import UrlInput from '@/components/media/UrlInput.vue';
import LoadingState from '@/components/ui/LoadingState.vue';
import MediaPreview from '@/components/media/MediaPreview.vue';
import MediaInfo from '@/components/media/MediaInfo.vue';
import DownloadOptions from '@/components/media/DownloadOptions.vue';
import Alert from '@/components/ui/Alert.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import { useMediaStore } from '@/stores/media';
import { useDownloadStore } from '@/stores/download';
import { useToastStore } from '@/stores/toast';
import type { DownloadOption, MediaMetadata } from '@/types';

const media = useMediaStore();
const downloadStore = useDownloadStore();
const toast = useToastStore();

const url = ref('');
const selectedOption = ref<DownloadOption | null>(null);
const downloading = ref(false);
const error = ref<string | null>(null);

const metadata = computed(() => media.metadata as MediaMetadata | null);
const analyzing = computed(() => media.loading);

const primaryFileSize = computed(() => metadata.value?.formats[0]?.file_size ?? null);

async function onPaste(value: string) {
    url.value = value;
    await analyze();
}

async function analyze() {
    try {
        await media.analyze(url.value);
        selectedOption.value = media.metadata!.formats[0] ?? null;
        toast.success('Media analyzed successfully.');
    } catch (e: unknown) {
        error.value = (e as { message?: string })?.message ?? 'Unable to analyze media.';
    }
}

async function download() {
    if (!metadata.value) return;

    downloading.value = true;
    error.value = null;

    const option = selectedOption.value ?? metadata.value.formats[0] ?? null;

    // Show the tracker immediately while the download is being processed.
    downloadStore.setCurrent({
        id: '',
        title: metadata.value.title,
        source_url: url.value,
        thumbnail_url: metadata.value.thumbnail_url,
        duration: metadata.value.duration,
        duration_human: null,
        resolution: option?.resolution ?? metadata.value.resolution ?? null,
        file_size: option?.file_size ?? null,
        file_size_human: null,
        media_type: metadata.value.media_type,
        format: option?.format ?? 'mp4',
        quality: option?.quality ?? null,
        status: 'processing',
        platform: null,
        file_url: null,
        metadata: metadata.value.raw,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
    });

    try {
        const created = await downloadStore.start(url.value, option?.format, option?.quality ?? option?.resolution ?? undefined);

        if (created.status === 'queued' || created.status === 'processing') {
            downloadStore.track(created.id);
        } else {
            toast.success('Download completed!');
        }

        url.value = '';
        selectedOption.value = null;
        downloading.value = false;
        media.clear();
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Unable to start the download.');
        downloading.value = false;
    }
}
</script>
