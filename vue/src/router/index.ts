import { createRouter, createWebHistory } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import routes from './routes'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

router.beforeEach((to, from) => {
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

router.afterEach((to, from) => {
    const languages = useLanguagesStore()
    if (to.params?.module && typeof to.params.module === 'string' && !languages.languages.modules[to.params.module]) {
        languages.fetchModuleLanguage(to.params.module)
    }
    // if (to.meta?.isLegacy && from.meta?.isLegacy) {
    //     router.go(0)
    //     return
    // }
})

export default router
