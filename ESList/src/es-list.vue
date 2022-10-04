<template>
    <v-app>
        <v-main>
            <div class="elevation-3 mb-2">
                <ESListFilters />
                <v-divider />
                <ESListHeader />
            </div>
            <ESListTable />
        </v-main>
    </v-app>
</template>

<script>
import store from './store/index'
import vuetify from './plugins/vuetify'
import ESListFilters from './components/es-list-filters'
import ESListHeader from './components/es-list-header'
import ESListTable from './components/es-list-table'

export default {
    store,
    vuetify,
    components: { ESListFilters, ESListHeader, ESListTable },
    created() {
        this.$store.commit('resetState')
        const data = document.querySelector('es-list').data // data passed from smarty
        this.$store.commit('setModule', data.module)
        this.$store.commit('setDefs', data.defs)
        this.$store.commit('setPreferences', data.preferences)
    },
}
</script>
<style lang="scss">
@import "../node_modules/vuetify/dist/vuetify.min.css";
@import url("https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700&display=swap');

// global styles
.v-application--wrap {
    min-height: auto;
}

.v-application .v-btn {
    font-size: 12px !important;
    font-weight: 700;
}

.v-application .text-body-1 {
    font-size: 16px !important;
    line-height: 1.5;
    letter-spacing: .5px;
}

.error--text {
    color: red !important;
}

.v-input--reverse .v-input__slot {
    flex-direction: row-reverse;
    justify-content: flex-end;
    .v-input--selection-controls__input {
        margin-right: 0;
        margin-left: 8px;
    }
}

.v-list-item__content .v-list-item__title {
    font-family: 'Roboto', sans-serif !important;
    font-size: 13px !important;
}

.v-select__selection {
    font-family: 'Roboto', sans-serif !important;
    font-size: 12px !important;
    letter-spacing: .4px !important;
}

.v-input__slot {
    margin: 0 !important;
}

.theme--dark.v-btn.v-btn--disabled.v-btn--has-bg {
    background-color: hsla(0, 0%, 50%, .5) !important;
    color: #eee !important;
}
</style>
