import { defineStore } from 'pinia';
import api from '@/services/api';
import type { Setting } from '@/types';

export const useSettingsStore = defineStore('settings', {
    state: (): { settings: Record<string, unknown>; loading: boolean } => ({
        settings: {},
        loading: false,
    }),

    getters: {
        get: (state) => (key: string, fallback: unknown = null): unknown => {
            return state.settings[key] ?? fallback;
        },
    },

    actions: {
        async fetch(): Promise<void> {
            if (Object.keys(this.settings).length > 0) {
                return;
            }

            this.loading = true;

            try {
                const { data } = await api.get<{ settings?: Setting[]; [k: string]: unknown }>('/settings');

                this.settings = data;
            } catch {
                this.settings = {};
            } finally {
                this.loading = false;
            }
        },
    },
});
