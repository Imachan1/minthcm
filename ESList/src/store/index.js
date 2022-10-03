import Vuex from 'vuex'

const getDefaultState = () => ({
    module: '',
    defs: { columns: {}, search: {} }, // defs from eslistsviewdefs.php
    preferences: {},
    isLoading: false,
    data: {
        total: 0,
        records: []
    },
    options: {
        page: 1,
        itemsPerPage: 10,
        pageOffsetMap: {},
        sortBy: '',
        sortOrder: 'asc',
        myObjects: false,
        searchPhrase: '',
        filters: { filter: [], must_not: [] },
    },
    tableOptions: {
        page: 1,
        itemsPerPage: 10,
    }
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
            state.preferences = preferences
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
            state.options = {
                ...state.options,
                page: 1,
                filters: filters || {}
            }
        },
        setOptions(state, options) {
            state.options = {
                ...state.options,
                ...options,
            }
        },
        setTableOptions(state, options) {
            state.tableOptions = {
                ...state.tableOptions,
                ...options,
            }
        },
        setOffset(state, offset) {
            state.options.pageOffsetMap[state.options.page] = offset
        },
        resetOffset(state) {
            state.options.pageOffsetMap = {}
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
        savedFiltersItems(state) {
            if (state.preferences?.saved_filters?.length) {
                return state.preferences.saved_filters.map(x => x.name)
            }
            return []
        },
    }
})
