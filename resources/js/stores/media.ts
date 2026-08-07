import { defineStore } from 'pinia';
import api from '@/services/api';
import type { MediaMetadata, Platform } from '@/types';

export const useMediaStore = defineStore('media', {
    state: () => ({
        metadata: null as MediaMetadata | null,
        loading: false,
        error: null as string | null,
    }),

    getters: {
        platformsForMedia: (state): Platform[] => {
            // Resolved client-side after the platforms store is hydrated.
            return [];
        },
    },

    actions: {
        async analyze(url: string): Promise<MediaMetadata> {
            this.loading = true;
            this.error = null;
            this.metadata = null;

            try {
                const { data } = await api.post('/media/analyze', { url });

                this.metadata = data.data;

                return data.data;
            } catch (e: unknown) {
                const message = (e as { message?: string })?.message ?? 'Unable to analyze the media.';
                this.error = message;
                throw e;
            } finally {
                this.loading = false;
            }
        },

        clear(): void {
            this.metadata = null;
            this.error = null;
        },
    },
});
