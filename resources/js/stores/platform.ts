import { defineStore } from 'pinia';
import api from '@/services/api';
import type { Platform } from '@/types';

export const usePlatformStore = defineStore('platform', {
    state: (): { platforms: Platform[]; loading: boolean } => ({
        platforms: [],
        loading: false,
    }),

    getters: {
        active: (state): Platform[] => state.platforms.filter((p) => p.is_active),
    },

    actions: {
        async fetch(): Promise<Platform[]> {
            if (this.platforms.length > 0) {
                return this.platforms;
            }

            this.loading = true;

            try {
                const { data } = await api.get('/platforms');

                this.platforms = data;

                return data;
            } finally {
                this.loading = false;
            }
        },
    },
});
