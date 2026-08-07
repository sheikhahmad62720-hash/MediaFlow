<template>
  <section class="py-16">
    <div class="container-page">
      <div class="mx-auto max-w-3xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">Frequently asked questions</h2>
        <p class="mt-4 text-slate-600 dark:text-slate-400">Quick answers to the most common questions about MediaFlow.</p>
      </div>

      <div class="mx-auto mt-10 max-w-3xl space-y-4">
        <Accordion
          v-for="item in items"
          :key="item.question"
          :title="item.question"
        >
          {{ item.answer }}
        </Accordion>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import Accordion from '@/components/ui/Accordion.vue';

const items = [
    {
        question: 'Is downloading media legal?',
        answer: 'It depends on your jurisdiction, the platform\'s Terms of Service, and the rights held by the content owner. We only process URLs that point to publicly available or explicitly authorized media. Please respect local law and creator rights before downloading.',
    },
    {
        question: 'Do you store the files?',
        answer: 'Yes, for a limited time. Completed downloads are kept for 7 days for you to retrieve, then automatically deleted. We never resell or redistribute your downloaded files.',
    },
    {
        question: 'Do I need an account?',
        answer: 'No. You can paste a URL and download anonymously. Creating an account lets you keep a history of your downloads and unlocks faster, higher limits.',
    },
    {
        question: 'Why can\'t I download from this platform?',
        answer: 'Some platforms do not offer direct download links or require authentication we cannot bypass. In those cases, the analyzer will report that no downloadable format is available for that source.',
    },
];
</script>
