import Vuex from 'vuex'

export default new Vuex.Store({
    state: {
        module: '',
        columns: {}, // columns defs from eslistviewdefs.php
        search: {}, // search defs from eslistviewdefs.php
        preferences: null, // user's eslist preferences for current module
        userColumns: null, // user's custom column layout
        data: {
            total: 0,
            records: []
        },
        options: {
            page: 1,
            itemsPerPage: 10,
            sortBy: '',
            sortOrder: 'asc',
            myObjects: false,
            searchPhrase: '',
            filters: [],
        }
    },
    mutations: {
        setModule(state, module) {
            state.module = module
        },
        setColumns(state, columns) {
            state.columns = columns
        },
        setSearch(state, search) {
            state.search = search || {}
        },
        setPreferences(state, preferences) {
            state.preferences = preferences
        },
        setUserColumns(state, columns) {
            state.userColumns = columns
        },
        setData(state, { total, results }) {
            state.data = {
                total: total,
                records: results || [],
            }
        },
        setFilters(state, filters) {
            state.options = {
                ...state.options,
                page: 1,
                filters: filters || []
            }
        },
        setOptions(state, options) {
            state.options = {
                ...state.options,
                page: 1,
                ...options,
            }
        }
    },
    actions: {
        async fetchResults({ commit, state }) {
            const result = await fetch(`index.php?module=${state.module}&action=ESList&page=1&itemsPerPage=10&function_name=getResults`)
            const data = await result.json()
            commit('setResults', data.results || [])
        }
    },
    getters: {
        getLabel: (state) => (label) =>
            SUGAR.language.languages[state.module]?.[label]
            ?? SUGAR.language.languages['app_strings']?.[label]
            ?? label,
        headers(state, getters) {
            const headers = []
            if (!state.userColumns) {
                // default
                for (const col in state.columns) {
                    if (!state.columns[col].default) {
                        continue
                    }
                    headers.push({
                        value: col,
                        text: getters.getLabel(state.columns[col].label),
                        sortable: !(state.columns[col].sortable === false)
                    })
                }
            } else {
                for (const col in state.userColumns) {
                    headers.push({
                        value: col,
                        text: getters.getLabel(state.userColumns[col].label),
                        sortable: !(state.userColumns[col].sortable === false)
                    })
                }
            }
            headers.push({
                value: 'actions',
                text: getters.getLabel('LBL_ACTIONS'),
                sortable: false,
                align: 'end'
            })
            return headers
        },
        links(state, getters) {
            const links = []
            for (const col in state.columns) {
                if (state.columns[col].link) {
                    links.push(col)
                }
            }
            return links
        },
        savedFiltersItems(state) {
            if (state.preferences?.saved_filters?.length) {
                return state.preferences.saved_filters.map(x => x.name)
            }
            return []
        },
        tableItems(state) {
            const items = []

            return items
        }
    }
})
