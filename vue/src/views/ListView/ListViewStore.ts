import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useUrlStore } from '@/store/url'
import { useBackendStore } from '@/store/backend'

interface Preferences {
    columns: string[]
}

interface Defs {
    columns: object
    search: object
}

export const useListViewStore = defineStore('listview', () => {
    const backend = useBackendStore()
    const url = useUrlStore()
    const isInit = ref(false)
    const config = ref({})
    const defs = ref<Defs | null>(null)
    const preferences = ref<Preferences | null>(null)
    const module = ref({})
    const results = ref([]) //todo: decode
    const isLoading = ref(false)

    async function init() {
        const result = await axios.post('/legacy/index.php?action=ESList', {
            module: url.module,
            function_name: 'getInitialData',
        })
        console.log('init lv', result.data)
        config.value = result.data?.config
        defs.value = result.data?.defs
        preferences.value = result.data?.preferences
        module.value = result.data?.module
        isInit.value = true
    }

    async function getData() {
        console.log('getData')
        isLoading.value = true
        const result = await axios.post('/legacy/index.php?action=ESList', {
            module: url.module,
            function_name: 'getResults',
            page: 1,
            itemsPerPage: 10,
            myObjects: false,
            searchPhrase: '',
            filters: {
                filter: [],
                must_not: [],
            },
            sortOrder: 'asc',
        })
        isLoading.value = false
        results.value = result.data?.results
        if (results.value?.length) {
            for (let i = 0; i <= 30; i++) {
                results.value.push(results.value[0])
            }
        }
        console.log('lv results', result.data)
    }

    const visibleColumns = computed(() => {
        return Object.values(defs.value?.columns).filter((col) => col.default)
        if (preferences.value?.columns && preferences.value?.columns.length) {
            // user preferences
            return preferences.value.columns.reduce((prev, curr) => {
                if (defs.value?.columns[curr]) {
                    return [...prev, defs.value?.columns[curr]]
                }
                return prev
            }, [])
        } else {
            // default
            return Object.values(defs.value?.columns).filter(
                (col) => col.default,
            )
        }
    })

    const headers = computed(() => {
        if (!isInit.value) {
            return {}
        }
        console.log('headers')
        const headers = visibleColumns.value.map((col) => ({
            value: col.name,
            key: col.name,
            title: col.label,
            sortable: !(col.sortable === false),
            class: col.name == 'name' ? 'stickyColumn' : '',
        }))
        headers.push({
            value: 'actions',
            key: 'actions',
            title: 'LBL_ESLIST_ACTIONS',
            sortable: false,
            align: 'end',
        })
        console.log('headers', headers)
        return headers
    })

    const links = computed(() => {
        return Object.values(defs.value?.columns)
            .filter((col) => col.link)
            .map((col) => ({
                nameField: col.name,
                urlField: `${col.name}_link`,
            }))
    })
    const booleans = computed(() => {
        return Object.values(defs.value?.columns)
            .filter((col) => ['bool', 'boolean'].includes(col.type))
            .map((col) => col.name)
    })
    const lists = computed(() => {
        return Object.values(defs.value?.columns)
            .filter((col) => col.type === 'enum' && col.options)
            .map((col) => ({
                field: col.name,
                options: backend.lang.app_list_strings[col.options],
            }))
    })
    const multienums = computed(() => {
        return Object.values(defs.value?.columns)
            .filter((col) => col.type === 'multienum' && col.options)
            .map((col) => ({
                field: col.name,
                options: backend.lang.app_list_strings(col.options),
            }))
    })
    const dates = computed(() => {
        return Object.values(defs.value?.columns)
            .filter((col) =>
                ['date', 'datetime', 'datetimecombo'].includes(col.type),
            )
            .map((col) => col.name)
    })
    const customFields = computed(() => {
        if (!isInit.value) {
            return {}
        }
        console.log({
            links: links.value,
            booleans: booleans.value,
            lists: lists.value,
            multienums: multienums.value,
            dates: dates.value,
        })
        return {
            links: links.value,
            booleans: booleans.value,
            lists: lists.value,
            multienums: multienums.value,
            dates: dates.value,
        }
    })

    return {
        init,
        getData,
        config,
        module,
        headers,
        results,
        customFields,
        isLoading,
    }
})
