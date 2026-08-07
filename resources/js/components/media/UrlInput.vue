<template>
  <div class="relative">
    <div class="relative">
      <Input
        v-model="internal"
        :placeholder="placeholder"
        :error="error"
        type="url"
        class="pr-12"
        @keydown.enter="emit('paste', internal)"
      />
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <Icon name="link" :size="18" class="text-slate-400" />
      </div>
      <div
        v-if="$attrs.loading"
        class="absolute inset-y-0 right-0 flex items-center pr-3"
      >
        <LoadingSpinner :loading="true" :size="18" />
      </div>
    </div>

    <InputError v-if="error" :message="error">
      <template #default><Icon name="alert-circle" :size="16" /></template>
    </InputError>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import Input from '@/components/ui/Input.vue';
import InputError from '@/components/ui/InputError.vue';
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';
import Icon from '@/components/ui/Icon.vue';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        placeholder?: string;
        loading?: boolean;
        error?: string;
    }>(),
    { modelValue: '', placeholder: 'Paste a media URL…' },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'paste', value: string): void;
}>();

const internal = ref(props.modelValue);

watch(
    () => props.modelValue,
    (val) => {
        internal.value = val;
    }
);

watch(internal, (val) => {
    emit('update:modelValue', val);
});
</script>
