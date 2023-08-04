import { RouteRecordRaw } from 'vue-router'
import AuthView from '@/views/AuthView/AuthView.vue'
import AuthViewForget from '@/views/AuthView/AuthViewForget.vue'
import LegacyView from '@/views/LegacyView/LegacyView.vue'
import AuthViewLogin from '@/views/AuthView/AuthViewLogin.vue'
import AuthViewReset from '@/views/AuthView/AuthViewReset.vue'

declare module 'vue-router' {
    interface RouteMeta {
        isLegacy?: boolean
        legacyUrl?: string
        auth: boolean
    }
}

const routes: Array<RouteRecordRaw> = [
    {
        path: '/auth',
        component: AuthView,
        meta: {
            auth: false,
        },
        children: [
            {
                path: 'login',
                alias: ['', '/Users/Login', '/modules/Users/Login'],
                name: 'auth-login',
                component: AuthViewLogin,
            },
            {
                path: 'forget',
                name: 'auth-forget',
                component: AuthViewForget,
            },
            {
                path: 'reset',
                name: 'auth-reset',
                component: AuthViewReset,
            },
        ],
    },
    {
        path: '/',
        name: 'dashboard',
        component: LegacyView,
        alias: ['/Home', '/modules/Home'],
        meta: {
            isLegacy: true,
            auth: true,
        },
    },
    {
        path: '/Calendar',
        name: 'calendar',
        component: LegacyView,
        alias: ['/modules/Calendar'],
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=Calendar',
            auth: true,
        },
    },
    {
        path: '/ResourceCalendar',
        name: 'resource-calendar',
        component: LegacyView,
        alias: ['/modules/ResourceCalendar'],
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=ResourceCalendar',
            auth: true,
        },
    },
    {
        path: '/ReservationsCalendar',
        name: 'reservations-calendar',
        component: LegacyView,
        alias: ['/modules/ReservationsCalendar'],
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=ReservationsCalendar',
            auth: true,
        },
    },
    {
        path: '/Administration',
        name: 'administration',
        component: LegacyView,
        alias: ['/Admin', '/modules/Administration'],
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=Administration',
            auth: true,
        },
    },
    {
        path: '/ModuleBuilder',
        name: 'modulebuilder',
        component: LegacyView,
        alias: ['/ModuleBuilder', '/modules/ModuleBuilder'],
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=ModuleBuilder&action=index',
            auth: true,
        },
    },
    {
        path: '/modules/:module',
        name: 'list',
        component: () => import('../views/ListView/ListView.vue'),
        alias: ['/modules/:module/ESListView', '/modules/:module/ListView', '/modules/:module/index'],
        meta: {
            auth: true,
        },
    },
    {
        path: '/modules/:module/:action/:record?',
        name: 'module-view',
        component: LegacyView,
        meta: {
            isLegacy: true,
            auth: true,
        },
    },
    {
        path: '/:catchAll(.*)',
        name: 'legacy',
        component: LegacyView,
        meta: {
            isLegacy: true,
            auth: true,
        },
    },
]

export default routes
