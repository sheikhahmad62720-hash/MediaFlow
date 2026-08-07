import { defineStore } from 'pinia';
import api from '@/services/api';
import type { AuthenticatedUser } from '@/types';

export const useAuthStore = defineStore('auth', {
    state: (): { user: AuthenticatedUser | null; token: string | null; loading: boolean } => ({
        user: null,
        token: localStorage.getItem('mediaflow_token'),
        loading: false,
    }),

    getters: {
        isAuthenticated: (state): boolean => state.user !== null,
        isAdmin: (state): boolean => state.user !== null && state.user.is_admin,
    },

    actions: {
        async me() {
            if (!this.token) {
                this.user = null;
                return;
            }

            this.loading = true;
            try {
                const { data } = await api.get('/auth/me');
                this.user = data.user;
            } catch {
                this.user = null;
                this.token = null;
                localStorage.removeItem('mediaflow_token');
            } finally {
                this.loading = false;
            }
        },

        async login(email: string, password: string) {
            this.loading = true;
            try {
                const { data } = await api.post('/auth/login', { email, password });
                this.token = data.token;
                this.user = data.user;
                localStorage.setItem('mediaflow_token', data.token);
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            this.loading = true;
            try {
                await api.post('/auth/logout');
            } catch {
                // ignore
            } finally {
                this.user = null;
                this.token = null;
                localStorage.removeItem('mediaflow_token');
                this.loading = false;
            }
        },
    },
});
