<template>
    <div>
        <v-scale-transition origin="center center 0">
            <ESListPopupDeletePrompt
                v-if="deleteConfirmationPopupData"
                :id="deleteConfirmationPopupData.id"
                :name="deleteConfirmationPopupData.name"
                @close-popup="deleteConfirmationPopupData = null"
            />
        </v-scale-transition>
        <v-data-table
            :headers="headers"
            :items="data.results"
            class="es-list-table elevation-1"
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
            checkbox-color="#009976"
            item-key="id"
            v-model="selected"
            :single-select="false"
            :loading="isLoading"
            :loading-text="label('LBL_LOADING')"
            :server-items-length="data.total"
        >
            <template v-slot:item.actions="{item}">
                <div class="d-flex justify-end" style="gap: 8px">
                    <v-icon v-if="item.acl_access.edit" @click="openEditViewInNewTab({ recordId: item.id })" small>mdi-pencil</v-icon>
                    <v-icon v-if="item.acl_access.view" @click="openDetailViewInNewTab({ recordId: item.id })" small>mdi-eye</v-icon>
                    <v-icon v-if="item.acl_access.delete" @click="openDeleteConfirmationPopup({ id: item.id, name: item.name })" small>mdi-delete</v-icon>
                </div>
            </template>
            <template v-for="link in customFields.links" v-slot:[`item.${link.nameField}`]="{item}">
                <span :key="link.nameField">
                    <a v-if="item[link.urlField]" :href="item[link.urlField]" v-html="item[link.nameField]" class="custom-link" />
                    <span v-else v-html="item[link.nameField]" />
                </span>
            </template>
            <template v-for="list in customFields.lists" v-slot:[`item.${list.field}`]="{item}">
                <span :key="list.field" v-text="list.options[item[list.field]]" />
            </template>
            <template v-for="bool in customFields.booleans" v-slot:[`item.${bool}`]="{item}">
                <v-icon :key="bool">
                    {{ (item[bool] && item[bool] !== '0') ? 'mdi-checkbox-marked-circle': 'mdi-close' }}
                </v-icon>
            </template>
            <template v-slot:header.data-table-select>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import ESListPopupDeletePrompt from './popups/es-list-popup-delete-prompt'

export default {
    components: { ESListPopupDeletePrompt },
    data: () => ({
        selected: [],
        deleteConfirmationPopupData: null,
    }),
    computed: {
        ...mapState({
            data: (state) => state.data,
            pageText(state) {
                const isOverflow = state.data.total > (this.options.page * this.options.itemsPerPage)
                const pageText = `{0} - {1} ${this.label('LBL_PAGE_TEXT')} {2}`
                return isOverflow ? `${pageText}+` : pageText
            },
            module: (state) => state.module,
            columnsDefs: (state) => state.defs.columns,
            isLoading: (state) => state.isLoading
        }),
        ...mapGetters({
            headers: 'headers',
            customFields: 'customFields',
            label: 'getLabel',
        }),
        options: {
            get() {
                return this.$store.state.tableOptions
            },
            set(val) {
                this.$store.commit('setTableOptions', val)
            },
        }
    },
    methods: {
        ...mapActions({
            openDetailViewInNewTab: 'openDetailViewInNewTab',
            openEditViewInNewTab: 'openEditViewInNewTab',
        }),
        openDeleteConfirmationPopup(data) {
            this.deleteConfirmationPopupData = data
        }
    },
    watch: {
        options: {
            handler() {
                this.$store.dispatch('getData')
            },
            deep: true
        }
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

    tbody > tr > td .v-icon::before {
        color: #009976;
    }

    .v-data-footer__icons-before {
        order: 1;
    }

    .v-data-footer__pagination {
        order: 2;
        margin: 10px !important;
        font-family: 'Roboto', sans-serif !important;
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }

    .v-data-footer__icons-after {
        order: 3;
    }

    .v-progress-linear__indeterminate.short.primary {
        background: #009976;
    }
    .v-progress-linear__indeterminate.long.primary {
        background: #009976;
    }
}
</style>
