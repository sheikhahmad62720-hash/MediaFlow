<template>
  <button
    v-if="authenticated"
    type="button"
    @click="open = true"
    class="focus-ring relative flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200"
  >
    <img
      v-if="user?.avatar_url"
      :src="user.avatar_url"
      :alt="user.name"
      class="h-full w-full rounded-full object-cover"
    />
    <Icon v-else name="users" :size="20" />
  </button>
  <button
    v-else
    type="button"
    @click="open = true"
    class="rounded-md px-3 py-1.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
  >
    Sign in
  </button>

  <teleport to="body">
    <Transition name="tween-opacity">
      <div
        v-if="open"
        class="fixed inset-0 z-40 flex items-center justify-center"
        @click="open = false"
      >
        <div
          class="w-72 rounded-xl bg-surface dark:bg-dark-card p-4 shadow-xl"
          @click.stop
        >
          <div v-if="authenticated" class="p-2 text-left">
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700">
                <Icon name="users" :size="22" />
              </div>
              <div>
                <p class="font-medium text-ink dark:text-slate-100">{{ user?.name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ user?.email }}</p>
              </div>
            </div>
            <nav class="mt-3 flex flex-col gap-1 text-sm">
              <RouterLink
                v-if="user?.is_admin"
                to="/admin"
                class="rounded-md px-2 py-1.5 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
                @click="open = false"
              >Admin dashboard</RouterLink>
              <RouterLink
                to="/account/downloads"
                class="rounded-md px-2 py-1.5 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
                @click="open = false"
              >My downloads</RouterLink>
              <button
                type="button"
                class="text-left rounded-md px-2 py-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30"
                @click="logout"
              >Sign out</button>
            </nav>
          </div>
          <div v-else class="p-2 text-center">
            <RouterLink
              to="/login"
              class="block w-full rounded-md bg-primary-600 py-2 text-sm font-medium text-white hover:bg-primary-700"
              @click="open = false"
            >Sign in</RouterLink>
          </div>
        </div>
      </div>
    </Transition>
  </teleport>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Icon from '@/components/ui/Icon.vue';

const auth = useAuthStore();
const open = ref(false);

const authenticated = computed(() => auth.isAuthenticated);
const user = computed(() => auth.user);

async function logout() {
    await auth.logout();
    open.value = false;
}
</script>
