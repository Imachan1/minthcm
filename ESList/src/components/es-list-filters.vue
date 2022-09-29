<template>
    <div class="pa-4">
        <v-scale-transition origin="center center 0">
            <ESListPopupSaveFilter
                v-if="saveFilterPopupVisible"
                @close-popup="saveFilterPopupVisible = false"
            />
        </v-scale-transition>
        <div class="es-list-filters-nav">
            <v-select
                v-model="activeFilter"
                dense
                class="flex-grow-0"
                :items="savedFiltersItems"
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
            <v-btn @click="saveFilter" outlined rounded text icon tile plain class="mr-4">
                <v-icon>mdi-content-save-outline</v-icon>
            </v-btn>
            <v-switch
                v-model="myObjects"
                @change="filter"
                color="#009976"
                class="pa-0 ma-0 mr-4 v-input--reverse"
                :label="label('LBL_MY_OBJECTS')"
                hide-details
            />
            <v-text-field
                v-model="searchPhrase"
                @keyup.enter="filter"
                dense
                :label="label('LBL_SEARCH')"
                outlined
                prepend-inner-icon="mdi-magnify"
                class="flex-grow-1"
                hide-details
            />
        </div>
        <div  class="es-list-filters mt-6">
            <ESListFilterRow
                ref="filters"
                v-for="(filter) in filters" :key="filter.id"
                :filter="filter"
                @filter-changed="activeFilter = null"
            />
        </div>
        <v-btn v-if="filters.length" @click="filter" class="mt-4" dark color="#009976">
            {{ label('LBL_FILTER') }}
        </v-btn>
    </div>
    
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import ESListFilterRow from './es-list-filter-row'
import ESListPopupSaveFilter from './popups/es-list-popup-save-filter'

export default {
    components: { ESListFilterRow, ESListPopupSaveFilter },
    data: () => ({
        myObjects: false,
        searchPhrase: '',
        filterRowsCount: 0,
        saveFilterPopupVisible: false,
        activeFilter: null,
        filters: [],
    }),
    computed: {
        ...mapState({
            search: (state) => state.search,
        }),
        ...mapGetters({
            label: 'getLabel',
            savedFiltersItems: 'savedFiltersItems',
        }),
    },
    methods: {
        filter() {
            this.$store.commit('setOptions', {
                myObjects: this.myObjects,
                searchPhrase: this.searchPhrase,
                page: 1,
            })
            if (this.$refs.filters) {
                const filters = []
                this.$refs.filters.forEach(filter => {
                    if (filter.isValid()) {
                        filters.push(...filter.getQSL())
                    }
                })
                console.log(filters)
                this.$store.commit('setFilters', filters)
            }
            this.$root.$emit('getResults')
        },
        saveFilter() {
            this.saveFilterPopupVisible = true
        },
        addFilter() {
            this.activeFilter = null
            this.filters.push({})
        },
        applySavedFilter(filterName) {
            let savedFilter = this.$store.state.preferences['saved_filters'].find(f => f.name === filterName)
            savedFilter = savedFilter?.filters ?? []
            const filters = []
            let x = new Date().getTime()
            savedFilter.forEach(f => {
                filters.push({...f, id: ++x})
            })
            this.filters = filters
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
