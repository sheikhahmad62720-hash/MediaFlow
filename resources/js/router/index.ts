import { createRouter, createWebHistory } from 'vue-router';
import type { RouteLocationNormalized, NavigationGuardNext } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useThemeStore } from '@/stores/theme';
import type { Pinia } from 'pinia';
import { routes } from '@/router/routes';

export const setupRouter = (pinia: Pinia) => {
    const router = createRouter({
        history: createWebHistory(),
        routes,
        scrollBehavior(to, _from, saved) {
            if (saved) {
                return saved;
            }
            if (to.hash) {
                return { el: to.hash, behavior: 'smooth' };
            }
            return { top: 0 };
        },
    });

    router.beforeEach((to: RouteLocationNormalized, _from, next: NavigationGuardNext) => {
        const auth = useAuthStore(pinia);

        if (to.meta.title) {
            document.title = `${String(to.meta.title)} — MediaFlow`;
        }

        if (to.meta.guestOnly && auth.isAuthenticated) {
            next({ name: 'home' });
            return;
        }

        if (to.meta.requiresAuth && !auth.isAuthenticated) {
            next({ name: 'login', query: { redirect: to.fullPath } });
            return;
        }

        if (to.meta.requiresAdmin && (!auth.isAuthenticated || !auth.isAdmin)) {
            next({ name: 'admin.login' });
            return;
        }

        useThemeStore(pinia).apply();
        next();
    });

    return router;
};
