import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useBackendStore } from './backend'
import { useUrlStore } from './url'
import { useLanguagesStore } from './languages'
import { useRoute } from 'vue-router'

/** backend defs */
export interface ModulesDefs {
    [name: string]: {
        name: string
        icon: string
        actions: ModuleAction[]
        vardefs: unknown
        metadata: ModuleMetadata
        acl: { [view: string]: number }
    }
}

export interface Modules {
    [name: string]: Module
}

export interface Module {
    name: string
    label: string
    icon: string
    actions: ModuleAction[]
    vardefs: unknown
    metadata: ModuleMetadata
    acl: { [view: string]: number }
}

export interface ModuleAction {
    name: string
    url: string
    action: string
    icon: string
}

interface SubpanelColumn {
    name: string
    label: string
    type: string
    usage?: string
}

export interface ModuleMetadata {
    Subpanels: {
        [key: string]: {
            properties: { [key: string]: string | number }
            columns: null | { [key: string]: SubpanelColumn }
        }
    }
    RecordView: any
}

export interface FieldVardef {
    name: string
    type: string
    label: string
    options?: string
    options_colors?: string
    default?: string
    readonly?: boolean
}

export const useModulesStore = defineStore('modules', () => {
    const route = useRoute()
    const backend = useBackendStore()
    const url = useUrlStore()
    const languages = useLanguagesStore()

    const modulesDefs = ref<ModulesDefs | null>(null)

    const defaultIcon = 'mdi-star'

    const modules = computed<Modules>(() => {
        const modules: Modules = {}
        if (!modulesDefs.value) {
            return modules
        }
        Object.values(modulesDefs.value).forEach((m) => {
            const label = languages.languages.app_list_strings['moduleList']?.[m.name] ?? m.name
            let icon = m.icon || defaultIcon
            if (m.icon?.slice(0, 4) !== 'mdi-') {
                icon = `mdi-${m.icon}`
            }
            modules[m.name] = {
                ...m,
                icon,
                label,
            }
        })
        return modules
    })

    const currentModule = computed(() => {
        const moduleName = route.params.module
        if (moduleName && typeof moduleName === 'string') {
            return modules.value[moduleName]
        }
        return null
    })

    const visibleModules = computed(() => {
        return backend.initData?.menu_modules.map((moduleName) => modules.value[moduleName]) ?? []
    })

    return {
        modules,
        modulesDefs,
        defaultIcon,
        visibleModules,
        currentModule,
    }
})
