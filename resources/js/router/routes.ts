import type { RouteRecordRaw } from 'vue-router';

export const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('@/components/layout/AppLayout.vue'),
        children: [
            { path: '', name: 'home', component: () => import('@/views/HomeView.vue'), meta: { title: 'Home' } },
            { path: 'download', name: 'downloader', component: () => import('@/views/DownloaderView.vue'), meta: { title: 'Downloader' } },
            { path: 'platforms', name: 'platforms', component: () => import('@/views/PlatformsView.vue'), meta: { title: 'Supported Platforms' } },
            { path: 'about', name: 'about', component: () => import('@/views/AboutView.vue'), meta: { title: 'About' } },
            { path: 'faq', name: 'faq', component: () => import('@/views/FaqView.vue'), meta: { title: 'FAQ' } },
            { path: 'contact', name: 'contact', component: () => import('@/views/ContactView.vue'), meta: { title: 'Contact' } },
            { path: 'privacy', name: 'privacy', component: () => import('@/views/PrivacyView.vue'), meta: { title: 'Privacy Policy' } },
            { path: 'terms', name: 'terms', component: () => import('@/views/TermsView.vue'), meta: { title: 'Terms of Service' } },
            { path: 'login', name: 'login', component: () => import('@/views/auth/LoginView.vue'), meta: { title: 'Login', guestOnly: true } },
            { path: 'account/downloads', name: 'account.downloads', component: () => import('@/views/account/DownloadsView.vue'), meta: { title: 'My Downloads', requiresAuth: true } },
            { path: ':pathMatch(.*)*', name: 'not-found', component: () => import('@/views/NotFoundView.vue'), meta: { title: 'Page not found' } },
        ],
    },
    {
        path: '/admin',
        component: () => import('@/components/layout/DashboardLayout.vue'),
        meta: { requiresAdmin: true },
        children: [
            { path: '', redirect: '/admin/dashboard' },
            { path: 'login', name: 'admin.login', component: () => import('@/views/auth/LoginView.vue'), meta: { title: 'Admin Login', guestOnly: true } },
            { path: 'dashboard', name: 'admin.dashboard', component: () => import('@/views/admin/DashboardView.vue'), meta: { title: 'Dashboard' } },
            { path: 'downloads', name: 'admin.downloads', component: () => import('@/views/admin/DownloadsView.vue'), meta: { title: 'Downloads' } },
            { path: 'messages', name: 'admin.messages', component: () => import('@/views/admin/MessagesView.vue'), meta: { title: 'Messages' } },
            { path: 'platforms', name: 'admin.platforms', component: () => import('@/views/admin/PlatformsView.vue'), meta: { title: 'Platforms' } },
            { path: 'logs', name: 'admin.logs', component: () => import('@/views/admin/ActivityLogsView.vue'), meta: { title: 'Activity Logs' } },
            { path: 'settings', name: 'admin.settings', component: () => import('@/views/admin/SettingsView.vue'), meta: { title: 'Settings' } },
            { path: ':pathMatch(.*)*', name: 'admin.not-found', component: () => import('@/views/NotFoundView.vue'), meta: { title: 'Page not found' } },
        ],
    },
];

export default routes;
