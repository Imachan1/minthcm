import { createRouter, createWebHashHistory } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import routes from './routes'

const router = createRouter({
    history: createWebHashHistory(window.location.pathname),
    routes,
})

// router.beforeEach((to, from) => {
//     const backend = useBackendStore()
//     const auth = useAuthStore()
//     // if (backend.initialLoading) {
//     //     return
//     // }
//     if (to.meta?.auth !== false && !auth.user?.id) {
//         return { name: 'auth-login' }
//     }
//     if (to.meta?.auth === false && auth.user?.id) {
//         return { name: 'dashboard' }
//     }
//     if (to.name === 'list') {
//         const module = to.params.module?.toString()
//         if (backend.initData?.legacy_views?.[module]?.list) {
//             return {
//                 name: 'module-view',
//                 params: {
//                     module,
//                     action: 'index',
//                 },
//             }
//         }
//     } else if (to.name === 'record') {
//         const module = to.params.module?.toString()
//         if (backend.initData?.legacy_views?.[module]?.record) {
//             return {
//                 name: 'module-view',
//                 params: {
//                     module,
//                     action: 'DetailView',
//                 },
//             }
//         }
//     }
// })

// router.afterEach((to, from) => {
//     const languages = useLanguagesStore()
//     if (to.params?.module && typeof to.params.module === 'string' && !languages.languages.modules[to.params.module]) {
//         languages.fetchModuleLanguage(to.params.module)
//     }
//     // if (to.meta?.isLegacy && from.meta?.isLegacy) {
//     //     router.go(0)
//     //     return
//     // }
// })

export default router
