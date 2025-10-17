import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { useBean } from '@/composables/useBean'
import { useACL } from '@/composables/useACL'
import { subpanelsApi } from '@/api/subpanels.api'
import { mintApi } from '@/api/api'
import { useBackendStore } from '@/store/backend'

interface Panel {
    component: string
    key?: string
    data?: unknown
}

interface RecordViewDefs {
    order: string[]
    panels: { [key: string]: Panel }
}

export interface Bean {
    id: string
    module_name: string
    acl_access: { [key: string]: boolean }
    attributes: { [key: string]: any }
    syncAttributes: { [key: string]: any }
    dirtyFields: Set<string>
}

interface RouteParams {
    module: string
    id: string
}

export const useRecordViewStore = defineStore('recordview', () => {
    const modulesStore = useModulesStore()
    const backendStore = useBackendStore()
    const route = useRoute()
    const acl = useACL()

    const defs = computed<RecordViewDefs>(() => {
        if (!modulesStore.currentModule) {
            return []
        }
        const recordViewDefs = modulesStore.currentModule.metadata.RecordView
        if (!recordViewDefs || typeof recordViewDefs !== 'object') {
            return []
        }
        return recordViewDefs
    })

    const module = computed(() => {
        if (typeof route.params.module === 'string') {
            return route.params.module
        }
        return ''
    })

    const recordId = computed(() => {
        if (typeof route.params.id === 'string') {
            return route.params.id
        }
        return ''
    })

    const bean = ref<ReturnType<typeof useBean>>(useBean(module.value, recordId.value))

    function resetBean() {
        bean.value = useBean(module.value, recordId.value)
        view.value = 'detail'
        subpanelsData.value = null
    }

    const view = ref<'detail' | 'edit' | 'list'>('detail')
    const inlineEditField = ref<string>('')
    const inlineEditFieldSaving = ref<string>('')

    const panels = computed(() => {
        let panels: Panel[] = []
        if (!defs.value?.panels || typeof defs.value.panels !== 'object') {
            return panels
        }
        if (!defs.value?.order || !defs.value.order.length) {
            panels = Object.values(defs.value?.panels ?? {})
        } else {
            panels = defs.value.order.map((key) => ({
                key,
                ...defs.value.panels[key],
            }))
        }
        return panels
    })

    //DEV: defs - (await mintApi.get('init')).data.modules.Candidates.metadata.Subpanels
    //DEV: data - (await mintApi.get('Candidates/subpanel/meetings/5ad6d7fd-e141-0a2c-4944-6449a8e50ad3')).data
    const subpanels = computed(() => {
        const languages = useLanguagesStore()
        const module = modulesStore.currentModule
        if (!module) {
            return []
        }
        const subpanelDefs = module.metadata.Subpanels
        if (!subpanelDefs || typeof subpanelDefs !== 'object') {
            return []
        }
        return Object.keys(subpanelDefs)
            .filter((key) => {
                const moduleType = subpanelDefs[key].properties?.module?.toString() || ''
                return acl.hasAccess(moduleType, 'list', true, true)
            })
            .map((key) => ({
                properties: subpanelDefs[key].properties,
                key,
                module: subpanelDefs[key].properties?.module?.toString() || '',
                label: subpanelDefs[key].properties?.title_key || '',
                columns: Object.entries(subpanelDefs[key].columns ?? {})
                    .filter(([col, props]) => props.usage !== 'query_only')
                    .map(([col, props]) => ({
                        ...(props || {}),
                        name: col,
                        label:
                            languages.label(
                                props.label || '',
                                subpanelDefs[key].properties?.module?.toString() || '',
                            ) || '',
                        type: props.type || '',
                    })),
                records: Object.keys(subpanelsData.value?.[key] ?? {}).map((id) => ({
                    ...(subpanelsData.value?.[key][id] || {}),
                    id,
                    parent_module: subpanelDefs[key].properties?.module?.toString() || '',
                })),
            records: Object.keys(subpanelsData.value?.[key] ?? {}).map((id) => ({
                ...(subpanelsData.value?.[key][id] || {}),
                id,
                parent_module: subpanelDefs[key].properties?.module?.toString() || '',
            })).filter((record) => record.id !== 'total' && record.id !== 'page'),
            page: subpanelsData.value?.[key]?.page || 0,
            total: subpanelsData.value?.[key]?.total || 0,
            paginateBy: backendStore.initData.global.list_max_entries_per_subpanel ? parseInt(backendStore.initData.global.list_max_entries_per_subpanel, 10) : 10
        }))
    })

    async function fetchSubpanelsData() {
        const route = useRoute()
        const data = await Promise.all(
            subpanels.value.map((subpanel) => {
                return subpanelsApi.fetchSubpanelsData(route.params.module, subpanel.key, route.params.id, subpanel.paginateBy, 0)
            }),
        )
        subpanelsData.value = subpanels.value.reduce((prev, curr, index) => {
            prev[curr.key] = data[index]?.data
            return prev
        }, {} as SubpanelsData)
    }

    async function fetchSubpanelRecords(subpanelKey: string, paginateBy: number, page: number) {
        const data = await subpanelsApi.fetchSubpanelsData(route.params.module, subpanelKey, route.params.id, paginateBy, page)
        if (!subpanelsData.value) subpanelsData.value = {}
        subpanelsData.value[subpanelKey] = data?.data
    }


    interface SubpanelsData {
        [key: string]: {
            [id: string]: {
                [property: string]: any
            }
        }
    }

    const subpanelsData = ref<SubpanelsData | null>(null)
    const columns = ref<number>(3)

    async function fetchLanguagesForSubpanels() {
        const languages = useLanguagesStore()
        subpanels.value.forEach((subpanel) => {
            languages.fetchModuleLanguage(subpanel.module)
        })
    }

    function updateField(field: string, value: any, additionalFields: { [fieldName: string]: any }) {
        const updatedValues = { [field]: value, ...(additionalFields || {}) }
        bean.value.updateFields(updatedValues)
    }

    return {
        defs,
        view,
        inlineEditField,
        inlineEditFieldSaving,
        bean,
        resetBean,
        panels,
        subpanels,
        fetchSubpanelsData,
        fetchLanguagesForSubpanels,
        columns,
        updateField,
        fetchSubpanelRecords,
    }
})
