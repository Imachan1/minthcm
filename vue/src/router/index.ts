import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import DashboardView from '@/views/DashboardView/DashboardView.vue'
import LoginView from '@/views/LoginView/LoginView.vue'
import LegacyView from '@/views/LegacyView/LegacyView.vue'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'

const routes: Array<RouteRecordRaw> = [
    // {
    //     path: '/',
    //     name: 'dashboard',
    //     component: DashboardView,
    //     alias: '/Home',
    // },
    {
        path: '/Users/Login',
        name: 'login',
        component: LoginView,
        meta: {
            auth: false,
        },
    },
    {
        path: '/user',
        name: 'user',
        component: () => import('@/views/UserProfileView/UserProfileView.vue'),
    },
    // {
    //     path: '/:module/DetailView/:record',
    //     name: 'detail',
    //     component: () => import('../views/DetailView/DetailView.vue'),
    // },
    {
        path: '/:module/EditView/:record',
        name: 'edit',
        component: () => import('../views/EditView/EditView.vue'),
        alias: '/:module/EditView',
    },
    // {
    //     path: '/:module',
    //     name: 'list',
    //     component: () => import('../views/ListView/ListView.vue'),
    //     alias: '/:module/index',
    // },
    {
        path: '/:catchAll(.*)',
        name: 'legacy',
        component: LegacyView,
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

router.beforeEach((to, from) => {
    console.log('router beforeEach')
    const backend = useBackendStore()
    const auth = useAuthStore()
    if (backend.initialLoading) {
        return
    }
    if (to.meta.auth !== false && !auth.user?.id) {
        return { name: 'login' }
    }
    if (to.name === 'login' && auth.user?.id) {
        return '/'
    }
})

// router.afterEach((to, from) => {
//     if (to.name === 'legacy' && from.name === 'legacy') {
//         router.go(0)
//         return
//     }
// })

export default router
