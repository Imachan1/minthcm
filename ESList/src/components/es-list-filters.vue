<template>
    <div class="pa-4">
        <v-scale-transition origin="center center 0">
            <ESListPopupSaveFilter
                v-if="saveFilterPopupVisible"
                @close-popup="saveFilterPopupVisible = false"
                @save-filter="saveFilter"
            />
        </v-scale-transition>
        <div class="es-list-filters-nav">
            <v-select
                v-model="activeFilter"
                dense
                class="flex-grow-0"
                :items="userFilters"
                item-text="name"
                item-value="name"
                @change="applySavedFilter"
                :label="label('LBL_SAVED_FILTERS')"
                outlined
                append-icon="mdi-chevron-down"
                hide-details
            />
            <v-btn @click="addFilter" class="" dark color="#009976">
                <v-icon dense left>mdi-plus</v-icon>
                {{ label('LBL_ADD_FILTER') }}
            </v-btn>
            <v-btn @click="showSaveFilterPopup" outlined rounded text icon tile plain class="mr-4">
                <v-icon>mdi-content-save-outline</v-icon>
            </v-btn>
            <v-switch
                v-model="myObjects"
                @change="updateOptions"
                color="#009976"
                class="pa-0 ma-0 mr-4 v-input--reverse"
                :label="label('LBL_MY_OBJECTS')"
                hide-details
            />
            <v-text-field
                v-model="searchPhrase"
                @keyup.enter="updateOptions"
                dense
                :label="label('LBL_SEARCH')"
                outlined
                prepend-inner-icon="mdi-magnify"
                class="flex-grow-1"
                hide-details
            />
        </div>
        <div class="es-list-filters mt-6">
            <ESListFilterRow
                v-for="row in filterRows"
                :key="row"
                :row="row"
                @filter-changed="activeFilter = null"
                @delete-filter-row="deleteFilterRow"
            />
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import ESListFilterRow from './es-list-filter-row'
import ESListPopupSaveFilter from './popups/es-list-popup-save-filter'
import * as operatorDefs from '../operators'

export default {
    components: { ESListFilterRow, ESListPopupSaveFilter },
    data: () => ({
        filterRowsCount: 0,
        saveFilterPopupVisible: false,
        activeFilter: null,
        filterRows: [],
    }),
    computed: {
        ...mapState({
            userFilters: (state) => state.preferences.saved_filters,
        }),
        ...mapGetters({
            label: 'getLabel',
        }),
        searchPhrase: {
            get() {
                return this.$store.state.options.searchPhrase
            },
            set(val) {
                this.$store.commit('setOptions', { searchPhrase: val })
            }
        },
        myObjects: {
            get() {
                return this.$store.state.options.myObjects
            },
            set(val) {
                this.$store.commit('setOptions', { myObjects: val })
            }
        },
    },
    methods: {
        updateOptions() {
            this.$store.commit('setOptions', { page: 1 }),
            this.$root.$emit('getResults')
        },
        showSaveFilterPopup() {
            this.saveFilterPopupVisible = true
        },
        saveFilter(filterName) {
            this.$store.commit('addSavedFilter', {
                name: filterName,
                filters: this.getFilters(),
            })
            this.$root.$emit('savePreferences')
            this.activeFilter = filterName
            this.saveFilterPopupVisible = false
        },
        addFilter() {
            this.activeFilter = null
            this.filterRows.push({
                field: null,
                operator: null,
                inputs: [],
            })
        },
        deleteFilterRow(row) {
            this.filterRows = this.filterRows.filter(filterRow => filterRow !== row)
        },
        applySavedFilter(filterName) {
            let savedFilter = this.$store.state.preferences['saved_filters'].find(f => f.name === filterName)
            savedFilter = savedFilter?.filters ?? []
            const filters = []
            savedFilter.forEach(f => {
                filters.push({...f})
            })
            this.filterRows = filters
        },
        getOperator(field, operator) {
            const type = this.$store.state.defs.search[field].type
            const defs = operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
            return defs[operator]
        },
        isFilterRowValid(row) {
            if (!row.field || !row.operator) {
                return false
            }
            const operator = this.getOperator(row.field, row.operator)
            if (!operator) {
                return false
            }
            if (operator.inputs && row.inputs.some(input => !input.value)) {
                return false
            }
            return true
        },
        replacePlaceholders(placeholders, inputs) {
            if (!inputs || !inputs.length) {
                return placeholders
            }
            let value = JSON.stringify(placeholders)
            inputs.forEach((input, i) => {
                value = value.replaceAll(`"{${i}}"`, JSON.stringify(input.value))
            })
            return JSON.parse(value)
        }
    },
    watch: {
        filterRows: {
            handler(newFilterRows) {
                const query = { filter: [], must_not: [] }
                newFilterRows
                    .filter(this.isFilterRowValid)
                    .forEach(row => {
                        const operator = this.getOperator(row.field, row.operator)
                        const filterType = operator.not ? 'must_not' : 'filter'
                        const esKey = this.$store.state.defs.search[row.field].key
                        operator.filters.forEach(f => {
                            query[filterType].push({
                                [f.op]: {
                                    [esKey]: this.replacePlaceholders(f.value, row.inputs)
                                }
                            })
                        })
                    })
                this.$store.commit('setFilters', query)
            },
            deep: true,
        }
    }
}
</script>

<style lang="scss">
.es-list-filters-nav {
    display: flex;
    align-items: center;
    gap: 16px;
}
.es-list-filters {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
</style>
