<template>
  <Transition name="slide-left">
    <div
      v-if="open"
      class="fixed inset-0 z-50 flex md:hidden"
      @click.self="close"
    >
      <div
        class="w-72 max-w-xs overflow-y-auto border-r border-slate-200 dark:border-slate-800 bg-surface dark:bg-dark-card p-4 shadow-xl"
      >
        <div class="flex items-center justify-between py-2">
          <Logo />
          <button
            type="button"
            @click="close"
            class="rounded-md p-1 text-ink hover:bg-slate-100 dark:hover:bg-slate-800"
          >
            <Icon name="x" :size="20" />
          </button>
        </div>

        <nav class="mt-6 flex flex-col gap-1 text-base font-medium">
          <RouterLink
            v-for="link in links"
            :key="link.to"
            :to="link.to"
            :class="navLinkClass(link.to)"
            @click="close"
          >
            {{ link.label }}
          </RouterLink>
        </nav>

        <div class="mt-8">
          <ThemeToggle />
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';
import ThemeToggle from '@/components/ui/ThemeToggle.vue';
import Icon from '@/components/ui/Icon.vue';

const props = defineProps<{ modelValue: boolean }>();
const emit = defineEmits<{ (e: 'update:modelValue', value: boolean): void }>();

const open = computed(() => props.modelValue);
const route = useRoute();

const links = [
    { to: '/', label: 'Home' },
    { to: '/download', label: 'Download' },
    { to: '/platforms', label: 'Platforms' },
    { to: '/about', label: 'About' },
    { to: '/faq', label: 'FAQ' },
    { to: '/contact', label: 'Contact' },
];

function close() {
    emit('update:modelValue', false);
}

function navLinkClass(to: string) {
    const active = route.path === to;
    return [
        'flex items-center gap-2 rounded-md px-3 py-2 transition-colors',
        active
            ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300'
            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
    ];
}
</script>
