<template>
  <section class="container-page py-12">
    <article class="prose dark:prose-invert prose-slate max-w-4xl">
      <h1>Contact</h1>

      <p>Have a question, a feature request, or found a platform that should be supported? Send us a message.</p>

      <ul>
        <li><strong>Email:</strong> <a href="mailto:hello@mediaflow.app">hello@mediaflow.app</a></li>
        <li><strong>Support:</strong> <a href="mailto:support@mediaflow.app">support@mediaflow.app</a></li>
      </ul>

      <form
        class="mt-8 grid gap-4"
        @submit.prevent="submit"
      >
        <div class="grid gap-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Name</label>
          <Input v-model="form.name" placeholder="Jane Doe" />
        </div>
        <div class="grid gap-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Email</label>
          <Input v-model="form.email" type="email" placeholder="jane@example.com" />
        </div>
        <div class="grid gap-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Message</label>
          <TextArea v-model="form.message" :rows="5" placeholder="How can we help?" />
        </div>

        <Button label="Send message" :loading="submitting" type="submit" />
      </form>
    </article>
  </section>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useToastStore } from '@/stores/toast';
import Input from '@/components/ui/Input.vue';
import TextArea from '@/components/ui/TextArea.vue';
import Button from '@/components/ui/Button.vue';

const toast = useToastStore();

const submitting = ref(false);
const form = reactive({ name: '', email: '', message: '' });

function submit() {
    submitting.value = true;
    setTimeout(() => {
        toast.success('Thanks for reaching out — we’ll reply shortly.');
        form.name = '';
        form.email = '';
        form.message = '';
        submitting.value = false;
    }, 500);
}
</script>
