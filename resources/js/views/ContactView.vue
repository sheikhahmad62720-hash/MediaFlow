<template>
  <div>
    <!-- Hero -->
    <section class="bg-canvas dark:bg-dark-canvas py-16">
      <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
          <Badge label="Contact us" color="primary" class="mx-auto" />
          <h1 class="mt-4 text-3xl font-bold tracking-tight text-ink dark:text-slate-100 sm:text-4xl">
            We'd love to hear from you
          </h1>
          <p class="mt-5 text-lg leading-relaxed text-slate-600 dark:text-slate-400">
            Have a question, a feature request, or found a platform that should be supported?
            Send us a message and we'll get back to you shortly.
          </p>
        </div>
      </div>
    </section>

    <section class="py-16">
      <div class="container-page">
        <div class="grid gap-8 lg:grid-cols-5">
          <!-- Contact info -->
          <div class="lg:col-span-2">
            <div class="grid gap-4">
              <Card v-for="channel in channels" :key="channel.title" :hover="false" class="flex items-start gap-4">
                <div
                  class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg"
                  :class="channel.iconClass"
                >
                  <Icon :name="channel.icon" :size="22" />
                </div>
                <div>
                  <h3 class="font-semibold text-ink dark:text-slate-100">{{ channel.title }}</h3>
                  <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ channel.body }}</p>
                  <a
                    v-if="channel.href"
                    :href="channel.href"
                    class="mt-1 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400"
                  >
                    {{ channel.value }}
                    <Icon name="external" :size="14" />
                  </a>
                  <p v-else class="mt-1 text-sm font-medium text-ink dark:text-slate-100">{{ channel.value }}</p>
                </div>
              </Card>

              <div class="rounded-xl border border-primary-200 bg-primary-50 p-5 dark:border-primary-900/40 dark:bg-primary-900/20">
                <div class="flex items-start gap-3">
                  <Icon name="info" :size="20" class="mt-0.5 shrink-0 text-primary-600 dark:text-primary-300" />
                  <div>
                    <h3 class="text-sm font-semibold text-ink dark:text-slate-100">Tip</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                      For the fastest answer, check the FAQ and supported platforms pages first — most
                      common questions are answered there.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Form -->
          <div class="lg:col-span-3">
            <Card :hover="false">
              <h2 class="text-xl font-semibold text-ink dark:text-slate-100">Send us a message</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Fields marked with * are required. We typically reply within 24 hours.
              </p>

              <form class="mt-6 grid gap-5" @submit.prevent="submit">
                <div class="grid gap-5 sm:grid-cols-2">
                  <div class="grid gap-2">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">
                      Name <span class="text-red-500">*</span>
                    </label>
                    <Input v-model="form.name" :error="errors.name" placeholder="Jane Doe" />
                    <p v-if="errors.name" class="text-xs text-red-600 dark:text-red-400">{{ errors.name }}</p>
                  </div>
                  <div class="grid gap-2">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">
                      Email <span class="text-red-500">*</span>
                    </label>
                    <Input v-model="form.email" type="email" :error="errors.email" placeholder="jane@example.com" />
                    <p v-if="errors.email" class="text-xs text-red-600 dark:text-red-400">{{ errors.email }}</p>
                  </div>
                </div>

                <div class="grid gap-2">
                  <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Subject</label>
                  <Select
                    v-model="form.subject"
                    :options="subjectOptions"
                    placeholder="Choose a topic (optional)"
                    :clearable="true"
                  />
                </div>

                <div class="grid gap-2">
                  <label class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    Message <span class="text-red-500">*</span>
                  </label>
                  <TextArea v-model="form.message" :rows="5" placeholder="Tell us a bit more..." />
                  <p v-if="errors.message" class="text-xs text-red-600 dark:text-red-400">{{ errors.message }}</p>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    We respect your privacy — your details are only used to reply to your message.
                  </p>
                  <Button label="Send message" :loading="submitting" type="submit" size="lg" />
                </div>
              </form>
            </Card>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import Badge from '@/components/ui/Badge.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Icon from '@/components/ui/Icon.vue';
import Input from '@/components/ui/Input.vue';
import TextArea from '@/components/ui/TextArea.vue';

const toast = useToastStore();

const submitting = ref(false);
const form = reactive({ name: '', email: '', subject: '', message: '' });
const errors = reactive<Record<string, string>>({});

const channels = [
    {
        icon: 'mail',
        title: 'General inquiries',
        body: 'Questions about features, partnerships or the platform.',
        value: 'hello@mediaflow.app',
        href: 'mailto:hello@mediaflow.app',
        iconClass: 'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-300',
    },
    {
        icon: 'users',
        title: 'Support',
        body: 'Help with a download or a platform not working as expected.',
        value: 'support@mediaflow.app',
        href: 'mailto:support@mediaflow.app',
        iconClass: 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-300',
    },
    {
        icon: 'clock',
        title: 'Response time',
        body: 'We typically reply within 24 hours on business days.',
        value: '24 hours',
        iconClass: 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300',
    },
];

const subjectOptions = [
    { label: 'General question', value: 'General question' },
    { label: 'Feature request', value: 'Feature request' },
    { label: 'Bug report', value: 'Bug report' },
    { label: 'Platform support request', value: 'Platform support request' },
    { label: 'Privacy / legal', value: 'Privacy / legal' },
    { label: 'Partnership', value: 'Partnership' },
    { label: 'Other', value: 'Other' },
];

function validate(): boolean {
    (Object.keys(errors) as Array<keyof typeof errors>).forEach((k) => delete errors[k]);

    if (!form.name.trim()) errors.name = 'Please provide your name.';
    if (!form.email.trim()) {
        errors.email = 'Please provide your email address.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please provide a valid email address.';
    }
    if (!form.message.trim()) errors.message = 'Please write a message.';
    else if (form.message.trim().length > 5000) errors.message = 'Message must be at most 5000 characters.';

    return Object.keys(errors).length === 0;
}

async function submit() {
    if (!validate()) return;

    submitting.value = true;
    try {
        await api.post('/contact', form);
        toast.success('Thanks for reaching out — we\'ll reply shortly.');
        form.name = '';
        form.email = '';
        form.subject = '';
        form.message = '';
    } catch (e: unknown) {
        const err = e as { response?: { data?: { message?: string } }; message?: string };
        toast.error(err.response?.data?.message ?? err.message ?? 'Failed to send message.');
    } finally {
        submitting.value = false;
    }
}
</script>
