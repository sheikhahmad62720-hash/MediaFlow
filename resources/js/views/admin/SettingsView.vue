<template>
  <section class="space-y-6">
    <h1 class="text-2xl font-bold text-ink dark:text-slate-100">Settings</h1>

    <Card v-for="group in groups" :key="group.key" class="p-5">
      <h3 class="mb-4 text-lg font-semibold text-ink dark:text-slate-100 capitalize">{{ group.key }}</h3>
      <div class="space-y-3">
        <div v-for="s in group.settings" :key="s.key" class="grid items-center gap-4 sm:grid-cols-3">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">{{ s.label ?? s.key }}</label>
            <p v-if="s.label" class="text-xs text-slate-500 dark:text-slate-400">{{ s.key }}</p>
          </div>
          <Input
            v-if="s.type !== 'boolean'"
            v-model="form[s.key]"
            :type="s.type === 'integer' ? 'number' : 'text'"
          />
          <label v-else class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form[s.key]" class="rounded border-slate-300" />
            <span class="text-sm">Enabled</span>
          </label>
          <div class="sm:col-span-3 text-right">
            <Button size="sm" :loading="saving[s.key] === true" @click="save(s.key)">Save</Button>
          </div>
        </div>
      </div>
    </Card>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useToastStore } from '@/stores/toast';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Button from '@/components/ui/Button.vue';

interface Setting extends Record<string, any> {
    key: string;
    label?: string;
    type?: string;
    value: any;
    group?: string;
}

const toast = useToastStore();
const groups = ref<Array<{ key: string; settings: Setting[] }>>([]);
const form = ref<Record<string, any>>({});
const saving = ref<Record<string, boolean>>({});

onMounted(async () => {
    const { data } = await api.get('/admin/settings');
    const dataEntry: Record<string, Setting> = data as Record<string, Setting>;
    const byGroup: Record<string, Setting[]> = {};

    Object.entries(dataEntry).forEach(([k, v]) => {
        form.value[k] = v.value;
        const g = v.group ?? 'general';
        (byGroup[g] = byGroup[g] || []).push({ ...v, key: k });
    });

    groups.value = Object.entries(byGroup).map(([key, settings]) => ({ key, settings }));
});

async function save(key: string) {
    saving.value[key] = true;
    try {
        const group = groups.value.find((g) => g.settings.some((s) => s.key === key))?.key ?? 'general';
        await api.put('/admin/settings', { key, value: form.value[key], group });
        toast.success('Saved.');
    } catch (e: unknown) {
        toast.error((e as { message?: string })?.message ?? 'Failed to save.');
    } finally {
        saving.value[key] = false;
    }
}
</script>