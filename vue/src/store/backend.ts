import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '@/composables/useApi'
import { useAuthStore, User } from './auth'
import { useAlertsStore } from './alerts'
import { useFavoritesStore } from './favorites'
import { useRecentsStore } from './recents'
import { useLanguagesStore, Languages } from './languages'
import axios, { AxiosError } from 'axios'
import { useModulesStore, ModulesDefs } from './modules'

interface QuickCreate {
    module: string
    name: string
}

interface InitResponse {
    user: User
    languages: Languages
    modules: ModulesDefs
    menu_modules: string[]
    quick_create: QuickCreate[]
}

export const useBackendStore = defineStore('backend', () => {
    const route = useRoute()
    const router = useRouter()
    const alerts = useAlertsStore()
    const favorites = useFavoritesStore()
    const recents = useRecentsStore()
    const languages = useLanguagesStore()
    const modules = useModulesStore()

    const initData = ref<InitResponse | null>(null)
    const initialLoading = ref(true)

    async function init() {
        const auth = useAuthStore()
        const api = useApi()
        try {
            const initResponse = await axios.get<InitResponse>('/api/init')
            initData.value = initResponse.data
            auth.user = initResponse.data?.user ?? {}
            languages.languages = {
                app_strings: initResponse.data.languages?.app_strings ?? {},
                app_list_strings: initResponse.data.languages?.app_list_strings ?? {},
                modules: {},
            }
            modules.modulesDefs = initResponse.data?.modules ?? {}
            if (route.meta.auth !== false && !auth.user?.id) {
                router.push({ name: 'auth-login' })
            } else if (route.name === 'auth-login' && auth.user?.id) {
                const prev = router.options.history.state.back as string
                if (prev && prev !== '/Users/Logout' && prev !== '/Users/Login') {
                    router.push(prev)
                } else {
                    router.push('/')
                }
            }
            alerts.init()
            favorites.fetch()
            recents.fetch()
        } catch (err) {
            if ((err as AxiosError).response?.status === 401) {
                const loginData = (await api.get('/api/login')).data
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: {
                        Users: loginData.languages?.Users ?? {},
                    },
                }
                if (router.currentRoute.value.name !== 'auth-login') {
                    router.push({ name: 'auth-login' })
                }
            }
        } finally {
            initialLoading.value = false
        }
    }

    return {
        init,
        initialLoading,
        initData,
    }
})
