import { ref, nextTick } from 'vue'
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
import { usePreferencesStore } from './preferences'

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
    global: any
}
export const useBackendStore = defineStore('backend', () => {
    const route = useRoute()
    const router = useRouter()
    const alerts = useAlertsStore()
    const favorites = useFavoritesStore()
    const recents = useRecentsStore()
    const languages = useLanguagesStore()
    const modules = useModulesStore()
    const preferences = usePreferencesStore()

    const initData = ref<InitResponse | null>(null)
    const initialLoading = ref(true)

    async function init() {
        console.log(router.resolve('#/modules/Calls/index'))
        setTimeout(() => {console.log(router.resolve('#/modules/Calls/index'))}, 3000)
        const auth = useAuthStore()
        const api = useApi()
        try {
            console.log('before', router.resolve('#/modules/Calls/index'))
            const initResponse = await axios.get<InitResponse>('api/init')
            console.log('after', router.resolve('#/modules/Calls/index'))
            initData.value = initResponse.data
            auth.user = initResponse.data?.user ?? {}
            languages.languages = {
                app_strings: initResponse.data.languages?.app_strings ?? {},
                app_list_strings: initResponse.data.languages?.app_list_strings ?? {},
                modules: {},
            }
            languages.currentLanguage = initResponse.data.global?.default_language ?? 'pl_PL'
            modules.modulesDefs = initResponse.data?.modules ?? {}
            await nextTick()
            await nextTick()
            await nextTick()
            await nextTick()
            await nextTick()
            console.log(location.hash)
            const routeName = router.resolve(location.hash)?.name
            console.log(routeName)
            if (route.meta.auth !== false && !auth.user?.id) {
                router.push({ name: 'auth-login' })
            } else if (routeName === 'auth-login' && auth.user?.id) {
                const prev = router.options.history.state.back as string
                if (prev && prev !== '/Users/Logout' && prev !== '/Users/Login') {
                    router.push(prev)
                } else {
                    router.push('/')
                }
            } else if (routeName === 'list') {
                const moduleName = route.params.module?.toString()
                if (initData.value?.legacy_views?.[moduleName]?.list) {
                    router.push({
                        name: 'module-view',
                        params: {
                            module: moduleName,
                            action: 'index',
                        },
                    })
                }
            } else if (routeName === 'record') {
                const moduleName = route.params.module?.toString()
                if (initData.value?.legacy_views?.[moduleName]?.record) {
                    router.push({
                        name: 'module-view',
                        params: {
                            module: moduleName,
                            action: 'DetailView',
                        },
                    })
                }
            }
            alerts.init()
            favorites.fetch()
            recents.fetch()
        } catch (err) {
            if ((err as AxiosError).response?.status === 401) {
                const loginData = (await api.get('api/login')).data
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: {
                        Users: loginData.languages?.Users ?? {},
                    },
                }
                preferences.global = loginData.global
                languages.currentLanguage = loginData.global?.default_language ?? 'pl_PL'
                if (router.currentRoute.value.meta?.auth !== false) {
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
