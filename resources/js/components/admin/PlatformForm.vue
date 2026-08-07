<template>
  <form class="space-y-4" @submit.prevent="emit('save', form)">
    <div class="space-y-2">
      <label class="text-sm font-medium">Name</label>
      <Input v-model="form.name" placeholder="YouTube" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Slug</label>
      <Input v-model="form.slug" placeholder="youtube" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Domain</label>
      <Input v-model="form.domain" placeholder="youtube.com" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Color (hex)</label>
      <Input v-model="form.color" placeholder="#FF0000" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Category</label>
      <Select v-model="form.category" :options="categories" placeholder="Select category" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Analyzer</label>
      <Select v-model="form.analyzer" :options="analyzers" placeholder="Select analyzer" />
    </div>
    <div class="space-y-2">
      <label class="text-sm font-medium">Description</label>
      <TextArea v-model="form.description" :rows="3" />
    </div>
    <div class="flex items-center gap-2">
      <input type="checkbox" v-model="form.is_active" id="active" class="rounded border-slate-300" />
      <label for="active" class="text-sm">Active</label>
    </div>
    <div class="flex justify-end gap-2 pt-4">
      <Button variant="secondary" type="button" @click="emit('cancel')">Cancel</Button>
      <Button type="submit" :loading="saving">{{ platform ? 'Update' : 'Create' }}</Button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import Input from '@/components/ui/Input.vue';
import TextArea from '@/components/ui/TextArea.vue';
import Select from '@/components/ui/Select.vue';
import Button from '@/components/ui/Button.vue';

const props = defineProps<{ platform?: any }>();
const emit = defineEmits<{ (e: 'save', data: any): void; (e: 'cancel'): void }>();

const saving = ref(false);

const form = ref({
    name: '',
    slug: '',
    domain: '',
    color: '#3f6df6',
    category: 'video',
    analyzer: 'demo',
    description: '',
    is_active: true,
});

const categories = [
    { label: 'Video', value: 'video' },
    { label: 'Music', value: 'music' },
    { label: 'Social', value: 'social' },
    { label: 'Image', value: 'image' },
    { label: 'Direct', value: 'direct' },
];
const analyzers = [
    { label: 'Direct (public URLs)', value: 'direct' },
    { label: 'Demo (simulated)', value: 'demo' },
];

watch(
    () => props.platform,
    (p) => {
        if (p) form.value = { ...p };
    },
    { immediate: true }
);
</script>