import Vuex from 'vuex'
import axios from 'axios'
import he from 'he'

const getDefaultState = () => ({
    url: 'index.php?action=ESList',
    module: '',
    defs: { columns: {}, search: {} }, // defs from eslistsviewdefs.php
    preferences: {},
    config: {},
    isLoading: false,
    tableOptions: {}, // v-data-table options (synced)
    data: { total: 0, results: [] },
    pageOffsetMap: {},
    myObjects: false,
    searchPhrase: '',
    filters: { filter: [], must_not: [] },
    getDataAbortController: new AbortController(), // used to cancel getData request
    selected: [],
})

export default new Vuex.Store({
    state: getDefaultState(),
    mutations: {
        resetState(state) {
            Object.assign(state, getDefaultState())
        },
        setModule(state, module) {
            state.module = module
        },
        setDefs(state, defs) {
            state.defs = defs
        },
        setPreferences(state, preferences) {
            state.preferences = preferences || {}
        },
        setConfig(state, config) {
            state.config = config
        },
        setSearchPhrase(state, searchPhrase) {
            state.searchPhrase = searchPhrase
        },
        setMyObjects(state, myObjects) {
            state.myObjects = myObjects
        },
        setColumnsPreference(state, columns) {
            Vue.set(state.preferences, 'columns', columns)
        },
        setDefaultColumns(state) {
            Vue.delete(state.preferences, 'columns')
        },
        setIsLoading(state, isLoading) {
            state.isLoading = isLoading
        },
        setData(state, data) {
            state.data = data
        },
        setFilters(state, filters) {
            state.filters = filters
        },
        updateTable(state) { // triggers getData
            state.tableOptions = {
                ...state.tableOptions,
                page: 1,
            }
        },
        setTableOptions(state, options) {
            state.tableOptions = {
                ...state.tableOptions,
                ...options,
            }
            state.preferences.items_per_page = options.itemsPerPage
        },
        setOffset(state, offset) {
            if (state.tableOptions.page === 1) {
                state.pageOffsetMap = {}
            }
            state.pageOffsetMap[state.tableOptions.page] = offset
        },
        addSavedFilter(state, filter) {
            if (!state.preferences) {
                state.preferences = {}
            }
            const filterWithSameNameIndex = state.preferences?.saved_filters?.findIndex(f => f.name === filter.name)
            if (filterWithSameNameIndex > -1) {
                state.preferences.saved_filters[filterWithSameNameIndex] = filter
                state.preferences = {
                    ...state.preferences,
                    saved_filters: [
                        ...state.preferences.saved_filters,
                    ]
                }
            } else {
                const savedFilters = state.preferences?.saved_filters ?? []
                state.preferences = {
                    ...state.preferences,
                    saved_filters: [
                        ...savedFilters,
                        filter,
                    ]
                }
            }
        },
        deleteSavedFilter(state, filterName) {
            state.preferences = {
                ...state.preferences,
                saved_filters: state.preferences.saved_filters.filter(f => f.name !== filterName)
            }
        },
        cancelGetDataRequest(state) {
            state.getDataAbortController.abort()
            state.getDataAbortController = new AbortController()
        },
        setSelected(state, selected) {
            state.selected = selected
        },
    },
    actions: {
        async callController({ state }, data) {
            try {
                const result = await axios.post(state.url, { module: state.module, ...data }, { signal: state.getDataAbortController.signal })
                return result.data
            } catch (err) {
                return { err }
            }
        },
        async getData({ getters, commit, dispatch }) {
            commit('cancelGetDataRequest')
            commit('setIsLoading', true)
            const startTime = new Date().getTime()
            const data = await dispatch('callController', {
                function_name: 'getResults',
                ...getters.params,
            })
            const requestTime = new Date().getTime() - startTime // ms
            if (data.err?.code === 'ERR_CANCELED') {
                return // do nothing on cancel request
            }
            if (data && !data.err) {
                const { results, total, offset } = data
                commit('setData', { results, total })
                commit('setOffset', offset)
            }
            const loadingAnimationCycle = 1100 // ms
            if (requestTime >= loadingAnimationCycle) {
                commit('setIsLoading', false)
            } else {
                // if request is faster than one cycle of animation, force one full cycle of loading animation
                setTimeout(() => { commit('setIsLoading', false) }, loadingAnimationCycle - requestTime)
            }
        },
        async savePreferences({ state, dispatch }) {
            await dispatch('callController', {
                function_name: 'savePreferences',
                preferences: state.preferences,
            })
        },
        async deleteRecord({ dispatch }, id) {
            await dispatch('callController', {
                function_name: 'deleteRecord',
                record_id: id,
            })
        },
        openDetailViewInNewTab({ state }, { recordId, module }) {
            if (recordId) {
                window.open(`index.php?module=${module || state.module}&action=DetailView&record=${recordId}`, '_blank')
            }
        },
        openEditViewInNewTab({ state }, { recordId, module }) {
            if (recordId) {
                window.open(`index.php?module=${module || state.module}&action=EditView&record=${recordId}`, '_blank')
            }
        }
    },
    getters: {
        getModuleLabel: (state) => (label) => SUGAR.language.languages[state.module]?.[label],
        getAppLabel: () => (label) => SUGAR.language.languages['app_strings']?.[label],
        getOptionsLabels: () => (options) => SUGAR.language.languages['app_list_strings']?.[options] || [],
        getLabel: (state, getters) => (label) => getters.getModuleLabel(label) || getters.getAppLabel(label) || label,
        params: (state) => ({
            page: state.tableOptions.page,
            itemsPerPage: state.tableOptions.itemsPerPage,
            myObjects: state.myObjects,
            searchPhrase: state.searchPhrase,
            filters: state.filters,
            offset: state.pageOffsetMap[state.tableOptions.page - 1],
            sortBy: state.defs.columns[state.tableOptions.sortBy[0]]?.key,
            sortOrder: state.tableOptions.sortDesc[0] ? 'desc' : 'asc',
        }),
        allColumns(state) {
            return Object.values(state.defs.columns)
                .sort((a, b) => a.label?.localeCompare(b.label, 'pl'))
        },
        filterableFields(state) {
            return Object.values(state.defs.search)
                .sort((a, b) => a.label?.localeCompare(b.label, 'pl'))
        },
        visibleColumns(state) {
            if (state.preferences.columns && state.preferences.columns.length) {
                // user preferences
                return state.preferences.columns.reduce((prev, curr) => {
                    if (state.defs.columns[curr]) {
                        return [...prev, state.defs.columns[curr]]
                    }
                    return prev
                }, [])
            } else {
                // default
                return Object.values(state.defs.columns).filter(col => col.default)
            }
        },
        headers(state, getters) {
            const headers = getters.visibleColumns.map(col => ({
                value: col.name,
                text: col.label,
                sortable: !(col.sortable === false),
            }))
            headers.push({
                value: 'actions',
                text: getters.getLabel('LBL_ESLIST_ACTIONS'),
                sortable: false,
                align: 'end'
            })
            return headers
        },
        links(state) {
            return Object.values(state.defs.columns)
                .filter(col => col.link)
                .map(col => ({
                    nameField: col.name,
                    urlField: `${col.name}_link`,
                }))
        },
        booleans(state) {
            return Object.values(state.defs.columns)
                .filter(col => ['bool', 'boolean'].includes(col.type))
                .map(col => col.name)
        },
        lists(state, getters) {
            return Object.values(state.defs.columns)
                .filter(col => col.type === 'enum' && col.options)
                .map(col => ({
                    field: col.name,
                    options: getters.getOptionsLabels(col.options)
                }))
        },
        multienums(state, getters) {
            return Object.values(state.defs.columns)
                .filter(col => col.type === 'multienum' && col.options)
                .map(col => ({
                    field: col.name,
                    options: getters.getOptionsLabels(col.options)
                }))
        },
        dates(state) {
            return Object.values(state.defs.columns)
                .filter(col => ['date', 'datetime', 'datetimecombo'].includes(col.type))
                .map(col => col.name)
        },
        customFields(state, getters) {
            return {
                links: getters.links,
                booleans: getters.booleans,
                lists: getters.lists,
                dates: getters.dates,
                multienums: getters.multienums,
            }
        },
        parsedResults(state) {
            const results = []
            if (state.data?.results?.length) {
                state.data.results.forEach(result => {
                    const item = { ...result }
                    for (const col in item) {
                        if (typeof item[col] === 'string') {
                            item[col] = he.decode(item[col])
                        }
                    }
                    results.push(item)
                })
            }
            return results
        }
    }
})
