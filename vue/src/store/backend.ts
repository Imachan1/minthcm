import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '@/composables/useApi'
import { useAuthStore } from './auth'
import { useUrlStore } from './url'
import { useAlertsStore } from './alerts'

interface Favorite {
    id: string
    module_name: string
    item_summary: string
}

interface Recent {
    item_id: string
    module_name: string
    item_summary: string
}

interface Language {
    app_strings: { [key: string]: string }
    app_list_strings: { [key: string]: object }
    modules: { [key: string]: { [key: string]: string } }
}

interface ModuleAction {
    title: string
    icon: string
    url: string
    options: []
}

interface Module {
    key: string
    label: string
    icon: string
    actions: ModuleAction[]
}

export const useBackendStore = defineStore('backend', () => {
    const initialLoading = ref(true)
    const favorites = ref<Favorite[]>([])
    const recents = ref<Recent[]>([])
    const modules = ref<Module[]>([])
    const route = useRoute()
    const router = useRouter()
    const url = useUrlStore()
    const alerts = useAlertsStore()

    const lang = ref<Language>({
        app_strings: {},
        app_list_strings: {},
        modules: {},
    })

    const label = computed(() => {
        return (label: string, module?: string) => {
            let lbl = ''
            if (module) {
                lbl = lang.value.modules?.[module]?.[label]
            }
            if (!lbl) {
                lbl = lang.value.app_strings?.[label]
            }
            return lbl || label
        }
    })

    async function init() {
        const auth = useAuthStore()
        const api = useApi()
        const initData = await api.get('index.php?entryPoint=MintVue')
        console.log('initData', initData.data)
        auth.user = initData.data?.user ?? {}
        lang.value = initData.data?.lang ?? {}
        favorites.value = initData.data?.favorites ?? []
        recents.value = initData.data?.recents ?? []
        modules.value = initData.data?.modules ?? []
        console.log('route', route)
        if (route.meta.auth !== false && !auth.user?.id) {
            router.push({ name: 'login' })
        } else if (route.name === 'login' && auth.user?.id) {
            // router.back()
            const prev = router.options.history.state.back as string
            console.log('prev', prev)
            if (prev && prev !== '/Users/Logout' && prev !== '/Users/Login') {
                router.push(prev)
            } else {
                router.push('/')
            }
        }
        alerts.init()
        initialLoading.value = false
    }

    const activeModule = computed(() => {
        return modules.value.find((m) => m.key === url.module)
    })

    return {
        init,
        initialLoading,
        favorites,
        recents,
        label,
        modules,
        activeModule,
    }
})
