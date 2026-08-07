<template>
  <section class="container-page flex min-h-[70vh] items-center justify-center py-12">
    <Card variant="bordered" class="w-full max-w-md p-8">
      <div class="mb-6 text-center">
        <Logo class="justify-center" />
        <h1 class="mt-3 text-2xl font-bold text-ink dark:text-slate-100">Welcome back</h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
          Sign in to manage your downloads and access the admin dashboard.
        </p>
      </div>

      <form class="space-y-4" @submit.prevent="login">
        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Email</label>
          <Input v-model="form.email" type="email" placeholder="admin@mediaflow.app" />
          <InputError v-if="errors.email" :message="errors.email" />
        </div>

        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Password</label>
          <Input v-model="form.password" type="password" placeholder="••••••••" />
          <InputError v-if="errors.password" :message="errors.password" />
        </div>

        <Button label="Sign in" :loading="loading" type="submit" />
      </form>

      <Alert
        v-if="loginError"
        type="error"
        :message="loginError"
      />

      <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
        Demo admin: <code class="rounded bg-slate-200 dark:bg-slate-800 px-1.5 py-0.5">admin@mediaflow.app</code> /
        <code class="rounded bg-slate-200 dark:bg-slate-800 px-1.5 py-0.5">admin123</code>
      </p>
    </Card>
  </section>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import Logo from '@/components/layout/Logo.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import InputError from '@/components/ui/InputError.vue';
import Button from '@/components/ui/Button.vue';
import Alert from '@/components/ui/Alert.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

const loading = ref(false);
const loginError = ref<string | null>(null);
const errors = reactive<Record<string, string>>({});

const form = reactive({ email: 'admin@mediaflow.app', password: 'admin123' });

async function login() {
    loginError.value = null;
    Object.keys(errors).forEach((k) => delete errors[k]);
    loading.value = true;

    try {
        await auth.login(form.email, form.password);
        await auth.me();
        router.replace({ name: 'admin.dashboard' });
    } catch (e: unknown) {
        const message = (e as { message?: string })?.message ?? 'Invalid credentials.';
        loginError.value = message;
    } finally {
        loading.value = false;
    }
}
</script>
