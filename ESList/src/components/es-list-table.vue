<template>
    <div>
        <v-scale-transition origin="center center 0">
            <ESListPopupConfirm
                v-if="deleteConfirmationPopupData"
                :body="`${label('LBL_ESLIST_DELETE_RECORD_CONFIRM_BODY')} ${deleteConfirmationPopupData.name}`"
                @confirm="deleteRecord"
                @close-popup="deleteConfirmationPopupData = null"
            />
        </v-scale-transition>
        <v-data-table
            :height="calculateListHeight()"
            :headers="headers"
            :items="parsedResults"
            class="es-list-table elevation-1"
            :options.sync="options"
            :footer-props="{
                itemsPerPageOptions: $store.state.config.config.itemsPerPageOptions,
                firstIcon: 'mdi-page-first',
                lastIcon: 'mdi-page-last',
                itemsPerPageText: label('LBL_ESLIST_ITEMS_PER_PAGE'),
                pageText: pageText,
            }"
            :header-props="{
                sortIcon: 'mdi-menu-up',
            }"
            checkbox-:color=""
            item-key="id"
            must-sort
            v-model="selected"
            :single-select="false"
            :loading="isLoading"
            :loading-text="label('LBL_ESLIST_LOADING')"
            :no-data-text="label('LBL_ESLIST_TABLE_NO_DATA')"
            :server-items-length="data.total"
            :show-select="massActions?.length"
        >
            <template v-slot:item.actions="{item}">
                <div class="d-flex justify-end" style="gap: 8px">
                    <v-icon
                        v-for="action in getItemActions(item)"
                        @click="action.onClick(item)"
                        :color="$store.state.config.theme.color['action-icon']"
                        small
                    >
                        {{ action.icon }}
                    </v-icon>
                </div>
            </template>
            <template v-for="link in customFields.links" v-slot:[`item.${link.nameField}`]="{item}">
                <span :key="link.nameField">
                    <a v-if="item[link.urlField]" :href="item[link.urlField]" v-text="item[link.nameField]" />
                    <span v-else v-text="item[link.nameField]" />
                </span>
            </template>
            <template v-for="list in customFields.lists" v-slot:[`item.${list.field}`]="{item}">
                <span :key="list.field" v-text="list.options[item[list.field]]" />
            </template>
            <template v-for="bool in customFields.booleans" v-slot:[`item.${bool}`]="{item}">
                <v-icon :key="bool" :color="$store.state.config.theme.color['boolean-icon']">
                    {{ (item[bool] && item[bool] !== '0') ? 'mdi-checkbox-marked-circle': 'mdi-close' }}
                </v-icon>
            </template>
            <template v-for="date in customFields.dates" v-slot:[`item.${date}`]="{item}">
                <span :key="date" v-text="formatDate(item[date])" />
            </template>
            <template v-slot:header.data-table-select>
            </template>
            <template v-for="multienum in customFields.multienums" v-slot:[`item.${multienum.field}`]="{item}">
                <span :key="multienum.field" v-text="formatMultienum(item[multienum.field], multienum.options)" />
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import ESListPopupConfirm from './popups/es-list-popup-confirm'

export default {
    components: { ESListPopupConfirm },
    data() {
        return {
            deleteConfirmationPopupData: null,
            coreActions: {
                edit: {
                    icon: 'mdi-pencil',
                    onClick: (item) => this.openEditViewInNewTab({ recordId: item.id }),
                },
                view: {
                    icon: 'mdi-eye',
                    onClick: (item) => this.openDetailViewInNewTab({ recordId: item.id }),
                },
                delete: {
                    icon: 'mdi-delete',
                    onClick: (item) => this.openDeleteConfirmationPopup({ id: item.id, name: item.name }),
                }
            },
        }
    },
    computed: {
        ...mapState({
            data: (state) => state.data,
            pageText(state) {
                const isOverflow = state.data.total > (this.options.page * this.options.itemsPerPage)
                const pageText = `{0} - {1} ${this.label('LBL_ESLIST_PAGE_TEXT')} {2}`
                return isOverflow ? `${pageText}+` : pageText
            },
            module: (state) => state.module,
            columnsDefs: (state) => state.defs.columns,
            isLoading: (state) => state.isLoading,
            actions: (state) => state.config.config.actions,
            massActions: (state) => state.config.config.mass_actions,
        }),
        ...mapGetters({
            headers: 'headers',
            customFields: 'customFields',
            label: 'getLabel',
            parsedResults: 'parsedResults',
        }),
        options: {
            get() {
                return this.$store.state.tableOptions
            },
            set(val) {
                this.$store.commit('setTableOptions', val)
            },
        },
        selected: {
            get () {
                return this.$store.state.selected
            },
            set (val) {
                this.$store.commit('setSelected', val)
            }
        },
        filtersHeight: {
            get () {
                return this.$store.state.filtersHeight
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
        },
        async deleteRecord() {
            if (this.deleteConfirmationPopupData?.id) {
                await this.$store.dispatch('deleteRecord', this.deleteConfirmationPopupData.id)
                this.$store.dispatch('getData')
            }
            this.deleteConfirmationPopupData = null
        },
        formatDate(date) {
            if (!date) {
                return ''
            }
            if (date.length === 10) { // db date
                return window.moment(date, 'YYYY-MM-DD').format(window.viewTools.date.getDateFormat())
            }
            if (date.length === 19) { // db datetime
                return window.moment(date, 'YYYY-MM-DD HH:mm:ss').format(window.viewTools.date.getDateTimeFormat())
            }
            return ''
        },
        getItemActions(item) {
            return this.actions
                .filter(action => typeof action !== 'string' || item.acl_access[action])
                .map(action => {
                    if (typeof action === 'string') {
                        return this.coreActions[action]
                    }
                    return {
                        ...action,
                        onClick: (item) => eval(action.onClick)(item)
                    }
                })
        },
        formatMultienum(value, labels) {
            return value.replaceAll('^', '').split(',').filter(label => label in labels).map(label => labels[label]).join(', ');
        },
        calculateListHeight() {
            const filtersHeight = this.filtersHeight;
            const otherElementsFixedHeight = 358
            const busySpace = filtersHeight + otherElementsFixedHeight
            return `calc(100vh - ${busySpace}px)` 
        },
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
        text-decoration: none;
    }
    tbody tr td {
        text-align: left;
        font-size: 14px !important;
        font-family: var(--font-body);
        letter-spacing: 0.25px !important;
        color: #00000099;
    }

    .v-data-footer__select {
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }
    .v-data-table-header th span {
        text-align: left;
        font-size: 12px !important;
        letter-spacing: .4px !important;
        color: #00000061;
    }

    .v-data-table-header .active span {
        text-align: left;
        font-size: 12px !important;
        letter-spacing: .4px !important;
        color: #00000099;
    }
    tbody tr td:nth-child(1) {
        text-align: left;
        font-size: 16px !important;
        letter-spacing: 0.14px !important;
        color: #000000DE;
    }

    .v-data-footer__icons-before {
        order: 1;
    }

    .v-data-footer__pagination {
        order: 2;
        margin: 10px !important;
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }

    .v-data-footer__icons-after {
        order: 3;
    }

    .v-progress-linear__indeterminate.short.primary {
        background: var(--color-loader);
    }
    .v-progress-linear__indeterminate.long.primary {
        background: var(--color-loader);
    }
    
    table > tbody > tr > td:nth-child(2),
    table > thead > tr > th:nth-child(2) {
        position: sticky !important;
        position: -webkit-sticky !important;
        left: 0;
        background: white;
    }
    table > tbody > tr > td:nth-child(2):hover,
    table > tbody > tr:hover td:nth-child(2),
    table > tbody > tr:hover {
        background: #F5F5F5!important;
    }
}
</style>
