<template>
  <section class="container-page py-12">
    <article class="prose dark:prose-invert prose-slate max-w-4xl">
      <h1>About MediaFlow</h1>
      <p>
        MediaFlow is a Universal Media Downloader Platform built for speed, privacy and legality. Our mission
        is to make media retrieval simple and transparent while respecting creators, platforms and the law.
      </p>

      <h2>How it works</h2>
      <ol>
        <li><strong>Paste</strong> a supported media URL — or a directly hosted media link.</li>
        <li><strong>Analyze</strong> the source to preview the title, thumbnail, duration and file size.</li>
        <li><strong>Download</strong> the preferred format; we store the file and you retrieve it via a signed link.</li>
      </ol>

      <h2>Legal &amp; ethical principles</h2>
      <ul>
        <li>We never bypass DRM, authentication, paywalls or rate limits.</li>
        <li>We only process content that is publicly available or explicitly authorized.</li>
        <li>We do not resell or redistribute any downloaded material.</li>
        <li>Generated downloads are retained for 7 days, then automatically deleted.</li>
      </ul>

      <h2>Technology</h2>
      <p>
        The platform runs on a modern stack — Laravel 13 REST API backed by SQLite, queued workers for the
        heavy lifting, and a Vue 3 + TypeScript single-page application styled with Tailwind CSS. The analyzer
        architecture is pluggable so partners can integrate official platform APIs without touching the core.
      </p>
    </article>
  </section>
</template>

<script setup lang="ts" />
