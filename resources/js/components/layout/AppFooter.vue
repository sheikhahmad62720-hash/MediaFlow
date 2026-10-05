<template>
  <footer class="border-t border-slate-200 bg-surface dark:border-slate-800 dark:bg-dark-card">
    <div class="container-page py-12">
      <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <Logo />
          <p class="mt-3 max-w-xs text-sm leading-relaxed text-slate-600 dark:text-slate-400">
            Fast, private media downloads from your favorite platforms — powered by a background
            queue.
          </p>
        </div>

        <!-- Product -->
        <div>
          <h3 class="text-sm font-semibold text-ink dark:text-slate-100">Product</h3>
          <ul class="mt-3 space-y-2 text-sm">
            <li><RouterLink to="/download" class="footer-link">Downloader</RouterLink></li>
            <li><RouterLink to="/platforms" class="footer-link">Supported platforms</RouterLink></li>
            <li><RouterLink to="/faq" class="footer-link">FAQ</RouterLink></li>
          </ul>
        </div>

        <!-- Company -->
        <div>
          <h3 class="text-sm font-semibold text-ink dark:text-slate-100">Company</h3>
          <ul class="mt-3 space-y-2 text-sm">
            <li><RouterLink to="/about" class="footer-link">About us</RouterLink></li>
            <li><RouterLink to="/contact" class="footer-link">Contact</RouterLink></li>
            <li><RouterLink to="/account/downloads" class="footer-link">My downloads</RouterLink></li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h3 class="text-sm font-semibold text-ink dark:text-slate-100">Legal</h3>
          <ul class="mt-3 space-y-2 text-sm">
            <li><RouterLink to="/privacy" class="footer-link">Privacy policy</RouterLink></li>
            <li><RouterLink to="/terms" class="footer-link">Terms of service</RouterLink></li>
          </ul>
        </div>
      </div>

      <div
        class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-6 text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:flex-row"
      >
        <span>© {{ year }} MediaFlow. All rights reserved.</span>
        <span class="flex items-center gap-4">
          <RouterLink to="/privacy" class="footer-link">Privacy</RouterLink>
          <RouterLink to="/terms" class="footer-link">Terms</RouterLink>
          <span class="text-slate-400 dark:text-slate-500">v{{ version }}</span>
        </span>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';

const year = computed(() => new Date().getFullYear());
const version = '1.0';
</script>
