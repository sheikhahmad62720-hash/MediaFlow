import { defineStore } from 'pinia';

type Theme = 'light' | 'dark' | 'system';

const STORAGE_KEY = 'mediaflow_theme';

export const useThemeStore = defineStore('theme', {
    state: (): { theme: Theme } => ({
        theme: (localStorage.getItem(STORAGE_KEY) as Theme) ?? 'system',
    }),

    getters: {
        isDark: (state): boolean => {
            if (state.theme === 'system') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            return state.theme === 'dark';
        },

        resolved: (state): 'light' | 'dark' => state.theme === 'system'
            ? window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            : state.theme,
    },

    actions: {
        set(theme: Theme): void {
            this.theme = theme;
            localStorage.setItem(STORAGE_KEY, theme);
            this.apply();
        },

        apply(): void {
            const root = document.documentElement;
            root.classList.toggle('dark', this.isDark);
        },
    },
});

