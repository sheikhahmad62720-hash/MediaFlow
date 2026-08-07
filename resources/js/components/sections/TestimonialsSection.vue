<template>
  <section class="bg-canvas dark:bg-dark-canvas py-16">
    <div class="container-page">
      <div class="mx-auto max-w-3xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-ink dark:text-slate-100">What users are saying</h2>
        <p class="mt-4 text-slate-600 dark:text-slate-400">Loved by developers, creators and everyday downloaders.</p>
      </div>

      <div class="mx-auto mt-12 grid gap-6 sm:grid-cols-2 lg:max-w-4xl lg:grid-cols-3">
        <TestimonialCard
          v-for="testimonial in testimonials"
          :key="testimonial.name"
          :name="testimonial.name"
          :role="testimonial.role"
          :quote="testimonial.quote"
          :avatar="testimonial.avatar"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import TestimonialCard from '@/components/sections/TestimonialCard.vue';

const testimonials = [
    {
        name: 'Alex Rivera',
        role: 'Indie creator',
        avatar: 'https://i.pravatar.com/48?img=11',
        quote: 'MediaFlow saves me hours when I need reference material for my videos. The metadata preview is spot on.',
    },
    {
        name: 'Sofia Kim',
        role: 'Product lead',
        avatar: 'https://i.pravatar.com/48?img=5',
        quote: 'The cleanest media downloader I have used. Fast, private, and the UI feels premium.',
    },
    {
        name: 'Noah Patel',
        role: 'Developer advocate',
        avatar: 'https://i.pravatar.com/48?img=12',
        quote: 'The queue-based architecture means the API never blocks — great developer experience.',
    },
];
</script>
