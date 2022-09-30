<template>
    <v-data-table
        :headers="headers"
        :items="data.records"
        class="es-list-table elevation-1"
        @update:options="updateOptions"
        :options.sync="options"
        :footer-props="{
            itemsPerPageOptions: [5, 10, 20, 30, 40, 50],
            firstIcon: 'mdi-page-first',
            lastIcon: 'mdi-page-last',
            itemsPerPageText: label('LBL_ITEMS_PER_PAGE'),
            pageText: pageText,
        }"
        :header-props="{
            sortIcon: 'mdi-menu-up',
        }"
        show-select
        checkbox-color="#009976"
        item-key="id"
        v-model="selected"
        :single-select="false"
        :loading="false"
        :loading-text="label('LBL_LOADING')"
        :server-items-length="data.total"
    >
        <template v-slot:item.actions>
            <v-icon small class="mr-2">mdi-pencil</v-icon>
            <v-icon small>mdi-eye</v-icon>
        </template>
        <template v-for="link in links" v-slot:[`item.${link}`]="{item}">
            <span class="custom-link" v-html="item[link]" :key="link"></span>
        </template>
        <template v-slot:header.data-table-select>
        </template>
    </v-data-table>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
    data: () => ({
        selected: [],
        options: {},
    }),
    computed: {
        ...mapState({
            data: (state) => state.data,
            pageText(state) {
                const isOverflow = state.data.total > (this.options.page * this.options.itemsPerPage)
                const pageText = `{0} - {1} ${this.label('LBL_PAGE_TEXT')} {2}`
                return isOverflow ? `${pageText}+` : pageText
            }
        }),
        ...mapGetters({
            headers: 'headers',
            label: 'getLabel',
            links: 'links',
        }),
    },
    methods: {
        updateOptions(newOptions) {
            this.$store.commit('setOptions', {
                page: newOptions.page,
                itemsPerPage: newOptions.itemsPerPage,
                sortBy: newOptions.sortBy[0] ?? '',
                sortOrder: newOptions.sortDesc[0] ? 'desc' : 'asc',
            })
            this.$root.$emit('getResults')
        },
    }
}
</script>

<style lang="scss">
.es-list-table {
    a {
        color: #009976 !important;
        text-decoration: none;
    }
    tbody tr td {
        text-align: left;
        font-size: 14px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: 0.25px !important;
        color: #00000099;
    }
    .v-data-footer__pagination {
        font-family: 'Roboto', sans-serif !important;
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }

    .v-data-footer__select {
        font-family: 'Roboto', sans-serif !important;
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }
    .v-data-table-header th span {
        text-align: left;
        font-size: 12px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: .4px !important;
        color: #00000061;
    }

    .v-data-table-header .active span {
        text-align: left;
        font-size: 12px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: .4px !important;
        color: #00000099;
    }
    tbody tr td:nth-child(2) {
        text-align: left;
        font-size: 16px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: 0.14px !important;
        color: #000000DE;
    }

    tbody > tr > td > .v-icon::before {
        color: #009976;
    }

    .v-data-footer__icons-before {
        order: 1;
    }

    .v-data-footer__pagination {
        order: 2;
        margin: 10px !important;
    }

    .v-data-footer__icons-after {
        order: 3;
    }
}
</style>
