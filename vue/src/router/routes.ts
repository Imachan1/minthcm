import { RouteRecordRaw } from 'vue-router'
import AuthView from '@/views/AuthView/AuthView.vue'
import LegacyView from '@/views/LegacyView/LegacyView.vue'

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'dashboard',
        component: LegacyView,
        alias: '/Home',
        meta: {
            isLegacy: true,
        },
    },
    {
        path: '/Administration',
        name: 'administration',
        component: LegacyView,
        alias: '/Admin',
        meta: {
            isLegacy: true,
        },
    },
    {
        path: '/Users/Login',
        name: 'login',
        component: AuthView,
        meta: {
            auth: false,
        },
        alias: ['/Login'],
    },
    {
        path: '/:module/ESListView',
        name: 'list',
        component: () => import('../views/ListView/ListView.vue'),
        // alias: '/:module/index',
    },
    {
        path: '/:catchAll(.*)',
        name: 'legacy',
        component: LegacyView,
        meta: {
            isLegacy: true,
        },
    },
]

export default routes
