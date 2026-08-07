import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
    state: () => ({
        toasts: [] as Array<{
            id: number;
            message: string;
            type: 'success' | 'error' | 'info';
            duration: number;
        }>,
    }),

    actions: {
        show(message: string, type: 'success' | 'error' | 'info' = 'info', duration = 4000): void {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type, duration });

            if (duration > 0) {
                setTimeout(() => this.remove(id), duration);
            }
        },

        remove(id: number): void {
            this.toasts = this.toasts.filter((t) => t.id !== id);
        },

        success(message: string, duration?: number): void {
            this.show(message, 'success', duration ?? 3500);
        },

        error(message: string, duration?: number): void {
            this.show(message, 'error', duration ?? 5000);
        },

        info(message: string, duration?: number): void {
            this.show(message, 'info', duration ?? 4000);
        },
    },
});
