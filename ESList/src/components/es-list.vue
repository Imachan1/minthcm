<template>
    <v-app>
        <v-main>
            <div>
                <div class="custom-menu mb-2">
                    <div class="custom-filters d-flex align-center pa-4">
                        <v-select
                                dense
                                class="custom-select mr-4"
                                label="Zapisane filtry"
                                outlined
                                append-icon="mdi-chevron-down"
                        >
                        </v-select>
                        <v-btn
                                class="custom-moje-filtry-btn mr-4"
                                color="#009976"
                        >
                            <v-icon
                                    dense
                                    left
                            >
                                mdi-plus
                            </v-icon>
                            DODAJ FILTR
                        </v-btn>
                        <v-btn
                                outlined
                                color="00000061"
                                class="custom-dodaj-filtr-btn mr-8 pa-0"

                        >
                            <v-icon>
                                mdi-content-save-outline
                            </v-icon>
                        </v-btn>
                        <span class="custom-tylko-moje-obiekty-span mr-4">Tylko moje obiekty</span>
                        <v-switch
                                v-model="myObjects"
                                @change="resetPagination"
                                color="#009976"
                                class="pa-0 ma-0 mr-8"
                        ></v-switch>
                        <v-text-field
                                v-model="searchPhrase"
                                @keyup.enter="resetPagination"
                                dense
                                label="Szukaj"
                                placeholder="Szukaj"
                                outlined
                                prepend-inner-icon="mdi-magnify"
                                class="custom-search"
                        >
                        </v-text-field>
                    </div>
                    <div class="custom-actions d-flex align-center pa-4">
                        <v-select
                                @change="handleMultiSelect"
                                :value="multiSelectOptions.value"
                                :items="multiSelectOptions"
                                dense
                                class="mr-4 custom-select"
                                label="Wybierz wiele"
                                outlined
                                append-icon="mdi-chevron-down"
                        >
                        </v-select>
                        <v-select v-show="selected.length"
                                  @change="handleMultiSelectAction"
                                  :value="multiSelectActions.value"
                                  :items="multiSelectActions"
                                  dense
                                  class="custom-select"
                                  label="Akcja masowa"
                                  outlined
                                  append-icon="mdi-chevron-down"
                        >
                        </v-select>
                        <v-btn
                                small
                                class="custom-kolumny-btn"
                                color="#009976"
                                outlined
                        >
                            <v-icon left>
                                mdi-playlist-plus
                            </v-icon>
                            KOLUMNY
                        </v-btn>
                    </div>
                </div>
                <v-data-table
                        :headers="headers"
                        :items="parsedResults"
                        class="elevation-1"
                        :options.sync="options"
                        @update:options="getResults"
                        :footer-props="{
                            itemsPerPageOptions: [5, 10, 20, 30, 40, 50],
                            showFirstLastPage: true,
                            firstIcon: 'mdi-page-first',
                            lastIcon: 'mdi-page-last',
                        }"
                        :header-props="{
                            sortIcon: 'mdi-menu-up',
                        }"
                        show-select
                        checkbox-color="#009976"
                        item-key="id"
                        v-model="selected"
                        :single-select="singleSelect"
                        :loading="loading"
                        loading-text="Ładowanie danych..."
                        :server-items-length="totalResults"
                >
                    <template v-slot:item.Akcje>
                        <v-icon
                                small
                                class="mr-2"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                                small
                        >
                            mdi-eye
                        </v-icon>
                    </template>
                    <template v-for="slot in slots" v-slot:[`item.${slot}`]="{item}">
                        <span class="custom-link" v-html="item[slot]"></span>
                    </template>
                    <template v-slot:header.data-table-select
                    >
                    </template>
                </v-data-table>
            </div>
        </v-main>
    </v-app>
</template>

<script>
    import vuetify from '../plugins/vuetify';

    export default {
        vuetify,
        name: 'Table',
        data() {
            return {
                selectAll: false,
                searchPhrase: '',
                myObjects: false,
                sortPending: false,
                sortBy: '',
                sortDesc: false,
                options: {},
                singleSelect: false,
                selected: [],
                loading: false,
                parsedResults: [],
                totalResults: 0,
                headers: [],
                results: [],
                defs: [],
                slots: [],
                multiSelectOptions: [
                    {text: 'Wybierz tę stronę', value: 'selectThisPage'},
                    {text: 'Wybierz wszystko', value: 'selectAll'},
                    {text: 'Odznacz wszystko', value: 'deselectAll'}
                ],
                multiSelectActions: [
                    {text: 'Masowa aktualizacja', value: 'massUpdate'},
                    {text: 'Eksportuj', value: 'export'},
                    {text: 'Usuń', value: 'delete'}
                ]
            }
        },
        watch: {
            results() {
                this.setResults();
            }
            ,
            totalResults(data) {
                this.totalResults = data;
            }
            ,
            loading(data) {
                this.loading = data;
            }
            ,
            defs(data) {
                this.defs = data;
            }
        },
        methods: {
            handleMultiSelect(data) {
                if (data === 'selectThisPage') {
                    this.selected = this.parsedResults;
                    this.parsedResults = this.parsedResults.map(x => ({...x, isSelectable: true }));
                    this.selectAll = false;
                } else if (data === 'selectAll') {
                    this.selected = this.parsedResults;
                    this.parsedResults = this.parsedResults.map(x => ({...x, isSelectable: false}));
                    this.selectAll = true;
                } else {
                    this.selected = [];
                    this.parsedResults = this.parsedResults.map(x => ({...x, isSelectable: true}));
                    this.selectAll = false;
                }
            },
            handleMultiSelectAction(data) {
                if (data === 'delete') {
                    if (this.selectAll) {
                        this.$emit('deleteAll', {
                            searchPhrase: this.searchPhrase,
                            myObjects: this.myObjects,
                            action: data
                        })
                    } else {
                        let IDs = this.getIDs();
                        this.$emit('deleteThisPage', {
                            IDs: IDs,
                            action: data
                        })
                    }
                }
            },
            getIDs() {
                return this.selected.map(item => {
                    return item.id;
                });
            },
            resetPagination() {
                this.options.page = 1;
                this.options.itemsPerPage = 10;
                this.getResults(this.options);
            },
            getResults(data) {
                data.myObjects = this.myObjects;
                data.searchPhrase = this.searchPhrase;
                this.$emit('getResults', data);
            },
            setResults() {
                let keys = Object.keys(this.defs[0]).map(item => item.toLocaleLowerCase());
                let parsedResults = [];
                for (let i = 0; i < this.results.length; i++) {
                    let result = {};
                    for (let j = 0; j < keys.length; j++) {
                        let key = keys[j];
                        if (key in this.results[i]) {
                            result[key] = this.results[i][key]
                        }
                    }
                    result.id = this.results[i].id;
                    parsedResults.push(result);
                    result = {};
                }
                this.parsedResults = parsedResults;
                if (this.selectAll) {
                    this.selected = this.parsedResults;
                    this.parsedResults = this.parsedResults.map(x => ({...x, isSelectable: false}));
                }
            }
        }
    }
</script>
<style>
    @import "../../node_modules/vuetify/dist/vuetify.min.css";
    @import url("https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700&display=swap');

    .v-application--wrap {
        min-height: auto;
    }

    .custom-link a {
        color: #009976 !important;
        text-decoration: none;
    }

    .v-list-item__content .v-list-item__title {
        font-family: 'Roboto', sans-serif !important;
        font-size: 13px !important;
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

    .v-select__selection {
        font-family: 'Roboto', sans-serif !important;
        font-size: 12px !important;
        letter-spacing: .4px !important;
    }

    .custom-tylko-moje-obiekty-span {
        text-align: left;
        font-family: 'Roboto', sans-serif !important;
        font-size: 16px !important;
        letter-spacing: .5px !important;
    }

    .custom-search .v-label {
        color: #0000001F;
    }

    .v-messages {
        display: none;
    }

    .custom-menu {
        box-shadow: 0px 3px 6px #00000029;
    }

    .custom-filters {
        border-bottom: 2px solid #DBDBDB;
    }

    .custom-actions {
        /*background: aqua;*/
    }

    .custom-dodaj-filtr-btn span {
        color: #00000061;
    }

    .custom-dodaj-filtr-btn {
        border: 1px solid #0000001F;
        border-radius: 4px;
        min-width: 40px !important;
    }

    .custom-moje-filtry-btn {
        text-align: left;
        font-family: "Roboto", sans-serif !important;
        font-size: 14px !important;
        letter-spacing: .25px !important;
        color: #FFFFFF !important;
    }

    .custom-select {
        flex: 0 auto;
        width: 200px !important;
        /*border: 1px solid #0000001F;*/
        /*border-radius: 4px;*/
    }

    .custom-search {
        border: 1px solid #0000001F;
        border-radius: 4px;
    }

    .custom-search fieldset {
        border: none;
    }

    /*.custom-select fieldset {*/
    /*    border: none;*/
    /*}*/

    .v-text-field__details {
        display: none;
    }

    .v-input__slot {
        margin: 0 !important;
    }

    .custom-kolumny-btn {
        margin-left: auto;
        border: 1px solid #0000001F;
        border-radius: 4px;
        font-size: 14px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: 1.25px !important;

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

    tbody tr td {
        text-align: left;
        font-size: 14px !important;
        font-family: "Roboto", sans-serif !important;
        letter-spacing: 0.25px !important;
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
</style>