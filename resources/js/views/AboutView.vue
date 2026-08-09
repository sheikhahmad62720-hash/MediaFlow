<template>
  <div>
    <!-- Hero -->
    <section class="bg-canvas dark:bg-dark-canvas py-16">
      <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
          <Badge label="About MediaFlow" color="primary" class="mx-auto" />
          <h1 class="mt-4 text-3xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-4xl">
            Fast, private and legal media downloads
          </h1>
          <p class="mt-5 text-lg leading-relaxed text-slate-600 dark:text-slate-400">
            MediaFlow is a Universal Media Downloader Platform built for speed, privacy and legality.
            Our mission is to make media retrieval simple and transparent while respecting creators,
            platforms and the law.
          </p>
          <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <Button as="a" href="/download" label="Start a download" variant="primary" size="lg" />
            <Button as="a" href="/platforms" label="View supported platforms" variant="outline" size="lg" />
          </div>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="py-16">
      <div class="container-page">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <Card v-for="stat in stats" :key="stat.label" class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-300">
              <Icon :name="stat.icon" :size="24" />
            </div>
            <p class="mt-4 text-2xl font-bold text-ink dark:text-slate-100">{{ stat.value }}</p>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ stat.label }}</p>
          </Card>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section class="bg-canvas dark:bg-dark-canvas py-16">
      <div class="container-page">
        <div class="mx-auto max-w-2xl text-center">
          <Badge label="How it works" color="primary" class="mx-auto" />
          <h2 class="mt-4 text-2xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-3xl">
            Three steps from link to file
          </h2>
          <p class="mt-4 text-slate-600 dark:text-slate-400">
            A deliberately simple workflow — paste, preview, download.
          </p>
        </div>

        <div class="mx-auto mt-12 grid max-w-5xl gap-6 md:grid-cols-3">
          <Card v-for="(step, i) in steps" :key="step.title" :hover="false" class="relative overflow-hidden">
            <span class="text-5xl font-bold text-primary-100 dark:text-primary-900/40">{{ i + 1 }}</span>
            <div class="mt-4 flex h-11 w-11 items-center justify-center rounded-lg bg-primary-600 text-white">
              <Icon :name="step.icon" :size="22" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink dark:text-slate-100">{{ step.title }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ step.body }}</p>
          </Card>
        </div>
      </div>
    </section>

    <!-- Legal & ethical principles -->
    <section class="py-16">
      <div class="container-page">
        <div class="grid items-center gap-12 lg:grid-cols-2">
          <div>
            <Badge label="Legal &amp; ethical principles" color="primary" />
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-3xl">
              Built to respect creators and the law
            </h2>
            <p class="mt-4 leading-relaxed text-slate-600 dark:text-slate-400">
              We take a principled approach to downloading. These commitments shape every part of the
              platform — from how we analyze sources to how long we store your files.
            </p>
            <div class="mt-6">
              <Button as="a" href="/faq" label="Read the FAQ" variant="outline" />
            </div>
          </div>

          <div class="grid gap-4">
            <Card
              v-for="principle in principles"
              :key="principle.title"
              :hover="false"
              class="flex items-start gap-4"
            >
              <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-300">
                <Icon :name="principle.icon" :size="20" />
              </div>
              <div>
                <h3 class="font-semibold text-ink dark:text-slate-100">{{ principle.title }}</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ principle.body }}</p>
              </div>
            </Card>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology -->
    <section class="bg-canvas dark:bg-dark-canvas py-16">
      <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
          <Badge label="Technology" color="primary" class="mx-auto" />
          <h2 class="mt-4 text-2xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-3xl">
            A modern stack, designed to scale
          </h2>
          <p class="mt-4 leading-relaxed text-slate-600 dark:text-slate-400">
            The platform runs on a modern stack — a Laravel 13 REST API backed by SQLite, queued workers
            for the heavy lifting, and a Vue 3 + TypeScript single-page application styled with Tailwind CSS.
            The analyzer architecture is pluggable so partners can integrate official platform APIs without
            touching the core.
          </p>
        </div>

        <div class="mx-auto mt-12 flex max-w-4xl flex-wrap items-center justify-center gap-3">
          <span
            v-for="tech in techStack"
            :key="tech"
            class="rounded-full border border-slate-200 bg-surface px-4 py-1.5 text-sm font-medium text-slate-700 dark:border-slate-800 dark:bg-dark-card dark:text-slate-300"
          >
            {{ tech }}
          </span>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import Badge from '@/components/ui/Badge.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Icon from '@/components/ui/Icon.vue';

const stats = [
    { value: '10+', label: 'Supported platforms', icon: 'globe' },
    { value: '7', label: 'Day file retention', icon: 'clock' },
    { value: '4K', label: 'Maximum quality', icon: 'sparkles' },
    { value: '100%', label: 'Free & anonymous', icon: 'shield-check' },
];

const steps = [
    {
        icon: 'copy-link',
        title: 'Paste a URL',
        body: 'Paste a supported media URL — or a directly hosted media link. No account needed.',
    },
    {
        icon: 'search',
        title: 'Analyze the source',
        body: 'Preview the title, thumbnail, duration and file size before committing to a download.',
    },
    {
        icon: 'download',
        title: 'Download the file',
        body: 'Choose your preferred format. We store the file and you retrieve it via a signed link.',
    },
];

const principles = [
    {
        icon: 'shield-check',
        title: 'No DRM, ever',
        body: 'We never bypass DRM, authentication, paywalls or rate limits.',
    },
    {
        icon: 'checkcircle',
        title: 'Public content only',
        body: 'We only process content that is publicly available or explicitly authorized.',
    },
    {
        icon: 'lock',
        title: 'No redistribution',
        body: 'We do not resell or redistribute any downloaded material.',
    },
    {
        icon: 'clock',
        title: 'Automatic cleanup',
        body: 'Generated downloads are retained for 7 days, then automatically deleted.',
    },
];

const techStack = ['Laravel 13', 'SQLite', 'Vue 3', 'TypeScript', 'Tailwind CSS', 'Queued Workers', 'yt-dlp', 'ffmpeg'];
</script>
