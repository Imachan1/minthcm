<template>
    <div class="pa-4">
        <v-scale-transition origin="center center 0">
            <ESListPopupSaveFilter
                v-if="saveFilterPopupVisible"
                :initialFilterName="activeFilter"
                @close-popup="saveFilterPopupVisible = false"
                @save-filter="saveFilter"
            />
        </v-scale-transition>
        <v-scale-transition origin="center center 0">
            <ESListPopupConfirm
                v-if="filterNameToDelete"
                :body="`${label('LBL_ESLIST_DELETE_FILTER_CONFIRM_BODY')} ${filterNameToDelete}`"
                @confirm="deleteSavedFilter"
                @close-popup="filterNameToDelete = null"
            />
        </v-scale-transition>
        <div class="es-list-filters-nav">
            <v-text-field
                class="col"
                :class="[$vuetify.breakpoint.xl ? 'col-6' : 'col-4']"
                v-model="searchPhrase"
                @keyup.enter="handleSearchPhraseEnterKey"
                @input="updateOptionsDebounce"
                dense
                :label="label('LBL_ESLIST_SEARCH')"
                outlined
                prepend-inner-icon="mdi-magnify"
                hide-details
            />
            <div
                class="col es-list-filters-nav"
                :class="[$vuetify.breakpoint.xl ? 'col-6' : 'col-8']"
            >
                <v-switch
                    v-model="myObjects"
                    @change="updateOptions"
                    :color="$store.state.config.theme.color.switch"
                    class="pa-0 ma-0"
                    :label="label('LBL_ESLIST_MY_OBJECTS')"
                    hide-details
                />
                <v-btn @click="addFilter" color="primary">
                    <v-icon dense left>mdi-plus</v-icon>
                    {{ label('LBL_ESLIST_ADD_FILTER') }}
                </v-btn>
                <v-btn
                    @click="showSaveFilterPopup"
                    outlined
                    :disabled="!filterRows.length"
                >
                    <v-icon dense left>mdi-content-save-outline</v-icon>
                    {{ label('LBL_ESLIST_SAVE_FILTER') }}
                </v-btn>
                <v-select
                    v-model="activeFilter"
                    :menu-props="{ contentClass: 'es-list-saved-filters-menu' }"
                    dense
                    :items="userFilters"
                    item-text="name"
                    item-value="name"
                    clearable
                    @click:clear="applySavedFilter('')"
                    :label="label('LBL_ESLIST_SAVED_FILTERS')"
                    :no-data-text="label('LBL_ESLIST_SAVED_FILTERS_NO_DATA')"
                    outlined
                    append-icon="mdi-chevron-down"
                    hide-details
                >
                    <template v-slot:item="{ item, on }">
                        <v-list-item-content v-on="on" @click="applySavedFilter(item.name)" class="pl-4">
                            {{ item.name }}
                        </v-list-item-content>
                        <v-list-item-action class="ma-0 mr-4">
                            <v-btn @click.stop="filterNameToDelete = item.name" icon>
                                <v-icon size="18">mdi-delete</v-icon>
                            </v-btn>
                        </v-list-item-action>
                    </template>
                </v-select>
            </div>
        </div>
        <div v-if="filterRows.length" class="es-list-filters mt-6">
            <ESListFilterRow
                v-for="row in filterRows"
                :key="row"
                :row="row"
                @delete-filter-row="deleteFilterRow"
            />
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import ESListFilterRow from './es-list-filter-row'
import ESListPopupSaveFilter from './popups/es-list-popup-save-filter'
import ESListPopupConfirm from './popups/es-list-popup-confirm'
import * as operatorDefs from '../operators'

export default {
    components: { ESListFilterRow, ESListPopupSaveFilter, ESListPopupConfirm },
    data: () => ({
        saveFilterPopupVisible: false,
        filterNameToDelete: null,
        activeFilter: null,
        filterRows: [],
        searchPhraseDebounceTimer: null,
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
                return this.$store.state.searchPhrase
            },
            set(val) {
                this.$store.commit('setSearchPhrase', val)
            }
        },
        myObjects: {
            get() {
                return this.$store.state.myObjects
            },
            set(val) {
                this.$store.commit('setMyObjects', val)
            }
        },
    },
    methods: {
        updateOptions() {
            this.$store.commit('updateTable')
        },
        updateOptionsDebounce() {
            clearTimeout(this.searchPhraseDebounceTimer)
            this.searchPhraseDebounceTimer = setTimeout(this.updateOptions, 1000)
        },
        handleSearchPhraseEnterKey() {
            clearTimeout(this.searchPhraseDebounceTimer)
            this.updateOptions()
        },
        showSaveFilterPopup() {
            this.saveFilterPopupVisible = true
        },
        saveFilter(filterName) {
            this.$store.commit('addSavedFilter', {
                name: filterName,
                filters: this.filterRows,
            })
            this.$store.dispatch('savePreferences')
            this.activeFilter = filterName
            this.saveFilterPopupVisible = false
        },
        addFilter() {
            this.filterRows.push({
                field: null,
                operator: null,
                inputs: [],
            })
        },
        deleteFilterRow(row) {
            this.filterRows = this.filterRows.filter(filterRow => filterRow !== row)
        },
        async applySavedFilter(filterName) {
            this.activeFilter = filterName
            let savedFilter = this.$store.state.preferences['saved_filters'].find(f => f.name === filterName)
            savedFilter = savedFilter?.filters ?? []
            this.filterRows = structuredClone(savedFilter)
            await this.$nextTick()
            this.$store.commit('updateTable')
        },
        getOperator(field, operator) {
            const type = this.$store.state.defs.search[field].type
            const defs = operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
            return defs[operator]
        },
        isInputValid(input) {
            return (
                input.value
                && (input.type !== 'date' || input.value.length === 10) // todo: date format validation
                && (input.type !== 'multiselect' || input.value.length)
            )
        },
        isFilterRowValid(row) {
            if (!row.field || !row.operator) {
                return false
            }
            const operator = this.getOperator(row.field, row.operator)
            if (!operator) {
                return false
            }
            if (operator.inputs && row.inputs.some(input => !this.isInputValid(input))) {
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
                value = value.replaceAll(`{${i}}`, input.value)
            })
            return JSON.parse(value)    
        },
        setFilters(filterRows) {
            const query = { filter: [], must_not: [] }
            filterRows
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
            const filtersChanged = JSON.stringify(query) !== JSON.stringify(this.$store.state.filters)
            this.$store.commit('setFilters', query)
            if (filtersChanged) {
                this.$store.dispatch('getData')
            }
        },
        deleteSavedFilter() {
            if (this.activeFilter === this.filterNameToDelete) {
                this.applySavedFilter('')
            }
            this.$store.commit('deleteSavedFilter', this.filterNameToDelete)
            this.$store.dispatch('savePreferences')
            this.filterNameToDelete = null
        }
    },
    watch: {
        filterRows: {
            handler(newFilterRows) {
                if (!newFilterRows.length) {
                    this.activeFilter = null
                }
                this.setFilters(newFilterRows)
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
.es-list-saved-filters-menu {
    .v-list-item.v-list-item--link {
        padding: 0px;
        min-height: unset;
    }
    .v-list-item__content {
        min-height: 40px;
    }
}
</style>
