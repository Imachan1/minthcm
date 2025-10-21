<template>
    <v-app :style="cssProps">
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
        const data = document.querySelector('es-list').data // data passed from smarty (ESListViewGeneric.tpl)
        this.$store.commit('setModule', data.module)
        this.$store.commit('setDefs', data.defs)
        this.$store.commit('setPreferences', data.preferences)
        this.$store.commit('setConfig', data.config)
        this.setInitialOptions(data)
        
    },
    computed: {
        cssProps() {
            const cssProps = {}
            const theme = this.$store.state.config.theme
            for (const property in theme) {
                for (const object in theme[property]) {
                    cssProps[`--${property}-${object}`] = theme[property][object]
                }
            }
            return cssProps
        }
    },
    methods: {
        setInitialOptions(data) {
            let itemsPerPage = data.preferences?.items_per_page || 10
            if (!data.config.config.itemsPerPageOptions.includes(itemsPerPage)) {
                itemsPerPage = data.config.config.itemsPerPageOptions[0]
            }
            this.$store.commit('setTableOptions', { itemsPerPage })
        }
    }
}
</script>
<style lang="scss">
@import "../node_modules/vuetify/dist/vuetify.min.css";
@import url("https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");

// global styles
.v-application--wrap {
    min-height: auto;
}

.v-application {
    [class*='text-'] {
        font-family: var(--font-body), sans-serif !important;
    }
    font-family: var(--font-body), sans-serif !important;
}

.v-application .v-btn {
    font-family: var(--font-btn), sans-serif !important;
    font-size: 12px !important;
    font-weight: 700;

    &.primary {
        background-color: var(--color-btn-primary--bg);
        color: var(--color-btn-primary--text);
    }
    &.v-btn--outlined {
        background-color: var(--color-btn-secondary--bg);
        color: var(--color-btn-secondary--text);
        border-color: var(--color-btn-secondary--outline);
    }
}

.v-application a {
    text-decoration: none;
    color: var(--color-text--link)
}

.v-application .v-list-item__title {
    font-family: var(--font-body), sans-serif !important;
    font-size: 12px !important;
    line-height: 1.2 !important;
}

.v-application .v-input--switch {
    .v-input--selection-controls__input {
        margin-right: 16px;
    }
}

.v-application .text-body-1 {
    font-size: 16px !important;
    line-height: 1.5;
    letter-spacing: .5px;
}

.v-application .v-date-picker-header__value {
    font-size: 12px !important;
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

.v-select__selection {
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
