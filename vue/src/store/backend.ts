import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '@/composables/useApi'
import { useAuthStore, User } from './auth'
import { useUrlStore } from './url'
import { useAlertsStore } from './alerts'
import { useFavoritesStore } from './favorites'
import { useRecentsStore } from './recents'
import { useLanguagesStore, Languages } from './languages'
import axios, { AxiosError } from 'axios'
import { MenuListItem } from '@/components/MintMenuList.vue'

export interface ModuleAction {
    name: string
    icon: string
    url: string
    action: string
    options: []
}

export interface Module {
    key: string
    label: string
    name: string
    icon: string
    actions: ModuleAction[]
}

interface QuickCreate {
    module: string
    name: string
}

interface InitResponse {
    user: User
    languages: Languages
    modules: Module[]
    quick_create: QuickCreate[]
}

export const useBackendStore = defineStore('backend', () => {
    const initialLoading = ref(true)
    const modules = ref<Module[]>([])
    const quickCreate = ref<MenuListItem[]>([])
    const route = useRoute()
    const router = useRouter()
    const url = useUrlStore()
    const alerts = useAlertsStore()
    const favorites = useFavoritesStore()
    const recents = useRecentsStore()
    const languages = useLanguagesStore()

    async function init() {
        const auth = useAuthStore()
        const api = useApi()
        try {
            const initData = await axios.get<InitResponse>('/api/init')
            console.log('initData', initData.data)
            auth.user = initData.data?.user ?? {}
            languages.languages = {
                app_strings: initData.data.languages?.app_strings ?? {},
                app_list_strings: initData.data.languages?.app_list_strings ?? {},
                modules: {},
            }
            modules.value = initData.data?.modules ?? []
            quickCreate.value =
                initData.data?.quick_create?.map((qc) => ({
                    icon: modules.value.find(m => m.label === qc.module)?.icon ?? 'pencil',
                    title: qc.name,
                    url: `/${qc.module}/EditView`,
                })) ?? []
            console.log('route', route)
            if (route.meta.auth !== false && !auth.user?.id) {
                router.push({ name: 'login' })
            } else if (route.name === 'login' && auth.user?.id) {
                // router.back()
                const prev = router.options.history.state.back as string
                console.log('prev', prev)
                if (
                    prev &&
                    prev !== '/Users/Logout' &&
                    prev !== '/Users/Login'
                ) {
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
                console.log('loginData', loginData)
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: {
                        Users: loginData.languages?.Users ?? {},
                    },
                }
                router.push({ name: 'login' })
            }
        } finally {
            initialLoading.value = false
        }
    }

    const activeModule = computed(() => {
        return modules.value.find((m) => m.label === url.module)
    })

    return {
        init,
        initialLoading,
        modules,
        quickCreate,
        activeModule,
    }
})
