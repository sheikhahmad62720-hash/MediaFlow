import { defineStore } from 'pinia';
import api from '@/services/api';
import type { Download, PaginatedResponse } from '@/types';

export const useDownloadStore = defineStore('download', {
    state: (): {
        downloads: Download[];
        current: Download | null;
        loading: boolean;
        polling: ReturnType<typeof setInterval> | null;
    } => ({
        downloads: [],
        current: null,
        loading: false,
        polling: null,
    }),

    getters: {
        isDownloadInProgress: (state): boolean => state.current !== null && ['queued', 'processing'].includes(state.current.status),
    },

    actions: {
        async start(url: string, format?: string, quality?: string): Promise<Download> {
            this.loading = true;

            try {
                const { data } = await api.post('/downloads', { url, format, quality });
                this.current = data.data;
                return data.data;
            } finally {
                this.loading = false;
            }
        },

        setCurrent(download: Download | null): void {
            this.stopPolling();
            this.current = download;
        },

        track(id: string): void {
            this.stopPolling();
            this.polling = setInterval(async () => {
                try {
                    const { data } = await api.get(`/downloads/${id}`);

                    this.current = data.data;

                    if (data.data.status === 'completed' || data.data.status === 'failed') {
                        this.stopPolling();
                    }
                } catch {
                    this.stopPolling();
                }
            }, 2000);
        },

        stopPolling(): void {
            if (this.polling) {
                clearInterval(this.polling);
                this.polling = null;
            }
        },

        async fetchHistory(params?: Record<string, string>): Promise<PaginatedResponse<Download>> {
            const { data } = await api.get('/downloads', { params });
            this.downloads = data.data;
            return data;
        },

        setList(downloads: Download[]): void {
            this.downloads = downloads;
        },

        reset(): void {
            this.stopPolling();
            this.current = null;
        },
    },
});
