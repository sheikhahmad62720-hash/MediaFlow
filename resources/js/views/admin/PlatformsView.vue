<template>
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Platforms</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">Manage supported platforms</p>
      </div>
      <Button variant="outline" size="sm" @click="openCreate = true">
        <Icon name="plus" :size="16" class="mr-1" />
        Add platform
      </Button>
    </div>

    <Card>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="text-left text-xs font-semibold text-slate-500 uppercase">
              <th class="p-3">Platform</th>
              <th class="p-3">Domain</th>
              <th class="p-3">Category</th>
              <th class="p-3">Analyzer</th>
              <th class="p-3">Active</th>
              <th class="p-3">Downloads</th>
              <th class="p-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            <tr v-for="p in platforms" :key="p.id">
              <td class="p-3">
                <div class="flex items-center gap-2">
                  <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: p.color }" />
                  <span class="font-medium text-ink dark:text-slate-100">{{ p.name }}</span>
                </div>
              </td>
              <td class="p-3 text-slate-600 dark:text-slate-400">{{ p.domain }}</td>
              <td class="p-3"><Badge :label="p.category" :color="categoryColor(p.category)" /></td>
              <td class="p-3 text-slate-600 dark:text-slate-400 capitalize">{{ p.analyzer }}</td>
              <td class="p-3">
                <input type="checkbox" :checked="p.is_active" @change="toggleActive(p)" class="rounded border-slate-300" />
              </td>
              <td class="p-3 text-slate-600">{{ p.download_count }}</td>
              <td class="p-3">
                <Button size="xs" variant="ghost" @click="openEditModal(p)">Edit</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>

    <Modal v-model:show="openCreate" title="New platform" @close="openCreate = false">
      <PlatformForm :platform="null" @save="savePlatform" @cancel="openCreate = false" />
    </Modal>

    <Modal v-model:show="openEdit" :title="editingPlatform ? 'Edit platform' : ''" @close="openEdit = false; editingPlatform = null">
      <PlatformForm :platform="editingPlatform" @save="savePlatform" @cancel="openEdit = false; editingPlatform = null" />
    </Modal>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import Card from '@/components/ui/Card.vue';
import Badge from '@/components/ui/Badge.vue';
import Button from '@/components/ui/Button.vue';
import Modal from '@/components/ui/Modal.vue';
import Icon from '@/components/ui/Icon.vue';
import PlatformForm from '@/components/admin/PlatformForm.vue';

const toast = useToastStore();
const platforms = ref<any[]>([]);
const openCreate = ref(false);
const openEdit = ref(false);
const editingPlatform = ref<any>(null);

async function load() {
    const { data } = await api.get('/admin/platforms');
    platforms.value = data;
}

function categoryColor(c: string): 'blue' | 'green' | 'red' | 'amber' | 'slate' {
    const map: Record<string, 'blue' | 'green' | 'red' | 'amber' | 'slate'> = {
        video: 'blue',
        music: 'green',
        social: 'blue',
        image: 'amber',
        direct: 'slate',
    };
    return map[c] ?? 'slate';
}

function toggleActive(p: any) {
    api.put(`/admin/platforms/${p.id}`, { ...p, is_active: !p.is_active }).then(load);
}

function openEditModal(p: any) {
    editingPlatform.value = { ...p };
    openEdit.value = true;
}

function savePlatform(payload: any) {
    const url = payload.id ? `/admin/platforms/${payload.id}` : '/admin/platforms';
    api.put(url, payload).then(() => {
        toast.success(payload.id ? 'Platform updated.' : 'Platform created.');
        load();
        openCreate.value = false;
        openEdit.value = false;
        editingPlatform.value = null;
    });
}

onMounted(load);
</script>