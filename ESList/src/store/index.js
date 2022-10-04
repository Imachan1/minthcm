import Vuex from 'vuex'
import axios from 'axios'

const getDefaultState = () => ({
    url: 'index.php?action=ESList',
    module: '',
    defs: { columns: {}, search: {} }, // defs from eslistsviewdefs.php
    preferences: {},
    isLoading: false,
    tableOptions: {}, // v-data-table options (synced)
    data: { total: 0, results: [] },
    pageOffsetMap: {},
    myObjects: false,
    searchPhrase: '',
    filters: { filter: [], must_not: [] },
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
    },
    actions: {
        async callController({ state }, data) {
            try {
                const result = await axios.post(state.url, { module: state.module, ...data })
                return result.data
            } catch (err) {
                console.error(err)
                return false
            }
        },
        async getData({ getters, commit, dispatch }) {
            commit('setIsLoading', true)
            const data = await dispatch('callController', {
                function_name: 'getResults',
                ...getters.params,
            })
            if (data) {
                const { results, total, offset } = data
                commit('setData', { results, total })
                commit('setOffset', offset)
            }
            commit('setIsLoading', false)
        },
        async savePreferences({ state, dispatch }) {
            await dispatch('callController', {
                function_name: 'savePreferences',
                preferences: state.preferences,
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
                .sort((a, b) => a.label.localeCompare(b.label, 'pl'))
        },
        filterableFields(state) {
            return Object.values(state.defs.search)
                .sort((a, b) => a.label.localeCompare(b.label, 'pl'))
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
                text: getters.getLabel('LBL_ACTIONS'),
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
        customFields(state, getters) {
            return {
                links: getters.links,
                booleans: getters.booleans,
                lists: getters.lists,
            }
        },
    }
})
