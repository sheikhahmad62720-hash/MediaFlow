<template>
  <div>
    <!-- Hero -->
    <section class="bg-canvas dark:bg-dark-canvas py-16">
      <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
          <Badge label="FAQ" color="primary" class="mx-auto" />
          <h1 class="mt-4 text-3xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-4xl">
            Frequently asked questions
          </h1>
          <p class="mt-5 text-lg leading-relaxed text-slate-600 dark:text-slate-400">
            Quick answers to the most common questions about MediaFlow. Can't find what you're looking
            for? Reach out and we'll be happy to help.
          </p>
          <div class="mt-8">
            <Button as="a" href="/contact" label="Contact support" variant="primary" size="lg" />
          </div>
        </div>
      </div>
    </section>

    <!-- Category tabs -->
    <section class="py-16">
      <div class="container-page">
        <div class="flex flex-wrap items-center justify-center gap-2">
          <button
            v-for="category in categories"
            :key="category.key"
            type="button"
            class="rounded-full border px-4 py-2 text-sm font-medium transition-colors"
            :class="
              activeCategory === category.key
                ? 'border-primary-600 bg-primary-600 text-white shadow'
                : 'border-slate-200 bg-surface text-slate-600 hover:border-primary-300 dark:border-slate-800 dark:bg-dark-card dark:text-slate-300'
            "
            @click="activeCategory = category.key"
          >
            {{ category.label }}
          </button>
        </div>

        <div class="mx-auto mt-12 max-w-3xl">
          <Card v-for="item in filteredItems" :key="item.question" :hover="false" class="mb-4">
            <div class="flex items-start gap-4">
              <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-300">
                <Icon :name="item.icon" :size="18" />
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-ink dark:text-slate-100">{{ item.question }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                  {{ item.answer }}
                </p>
              </div>
            </div>
          </Card>

          <div class="mt-8 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center">
            <Icon name="mail" :size="28" class="mx-auto text-primary-500" />
            <h3 class="mt-3 text-lg font-semibold text-ink dark:text-slate-100">Still have questions?</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
              We're happy to answer anything about MediaFlow, supported platforms or downloads.
            </p>
            <div class="mt-5">
              <Button as="a" href="mailto:hello@mediaflow.app" label="hello@mediaflow.app" variant="primary" />
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import Badge from '@/components/ui/Badge.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Icon from '@/components/ui/Icon.vue';

const categories = [
    { key: 'all', label: 'All' },
    { key: 'general', label: 'General' },
    { key: 'legality', label: 'Legality' },
    { key: 'accounts', label: 'Accounts' },
    { key: 'files', label: 'Files' },
];

const activeCategory = ref('all');

const items = [
    {
        icon: 'shield-check',
        category: 'legality',
        question: 'Is downloading media legal?',
        answer:
            'Legality depends on your jurisdiction, the platform\'s Terms of Service, and the rights held by the content owner. We only process URLs pointing to publicly available or explicitly authorized media. Please always respect local law and creator rights before downloading.',
    },
    {
        icon: 'users',
        category: 'accounts',
        question: 'Do I need an account?',
        answer:
            'No. Anonymous users can paste a URL, retrieve metadata and download immediately. An account unlocks a persistent download history, higher limits and future premium features.',
    },
    {
        icon: 'clock',
        category: 'files',
        question: 'How long are files kept?',
        answer:
            'Generated downloads are stored securely for 7 days. After that, an automated job permanently deletes them. We never keep your files longer than necessary.',
    },
    {
        icon: 'download',
        category: 'general',
        question: 'Why does some media show no download formats?',
        answer:
            'Some platforms do not expose direct download links or require authentication we cannot bypass. In those cases the analyzer reports that no downloadable format is available for that specific source.',
    },
    {
        icon: 'sparkles',
        category: 'general',
        question: 'Which platforms are supported?',
        answer:
            'MediaFlow supports YouTube, Vimeo, SoundCloud, Spotify, TikTok, Instagram, Dailymotion, X/Twitter, Pexels, Pixabay and directly hosted media links. Check the platforms page for the full, up-to-date list.',
    },
    {
        icon: 'checkcircle',
        category: 'files',
        question: 'Which formats and qualities are available?',
        answer:
            'Depending on the source, MP4 video is available from 360p up to 4K, along with M4A audio. The analyzer lists every downloadable option with its resolution and file size before you commit.',
    },
    {
        icon: 'lock',
        category: 'accounts',
        question: 'Is my data kept private?',
        answer:
            'Yes. Downloads are stored securely, are retrievable only through signed links, and are permanently deleted after 7 days. We do not sell or redistribute your data or downloaded files.',
    },
];

const filteredItems = computed(() =>
    activeCategory.value === 'all' ? items : items.filter((item) => item.category === activeCategory.value),
);
</script>
