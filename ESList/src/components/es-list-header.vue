<template>
    <div>
        <v-scale-transition origin="center center 0">
            <ESListPopupColumns
                v-if="columnsPopupVisible"
                @close-popup="columnsPopupVisible = false"
            />
        </v-scale-transition>
        <div class="es-list-header">
        <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                color="primary"
                dark
                v-bind="attrs"
                v-on="on"
                >
                {{ label('LBL_ESLIST_MASS_ACTION') }}
                </v-btn>
            </template>
            <v-list>
                <v-list-item
                v-for="(item, index) in mass_actions"
                :key="index"
                @click="performMassAction(item.action)"
                >
                <v-list-item-title>{{ label(item.label) }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
            <v-select
                v-show="false /*todo*/"
                @change="null"
                :value="null"
                :items="[]"
                dense
                class=""
                :label="label('LBL_ESLIST_MASS_ACTION')"
                outlined
                append-icon="mdi-chevron-down"
                hide-details
            />
            <v-btn
                small
                class="ms-auto"
                outlined
                @click="columnsPopupVisible = true"
            >
                <v-icon left>mdi-playlist-plus</v-icon>
                {{ label('LBL_ESLIST_COLUMNS') }}
            </v-btn>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import ESListPopupColumns from './popups/es-list-popup-columns'

export default {
    components: { ESListPopupColumns },
    data: () => ({
        columnsPopupVisible: false
    }),
    computed: {
        ...mapState({
            mass_actions: (state) => state.config.config.mass_actions,
            selected: (state) => state.selected,
        }),
        ...mapGetters({
            label: 'getLabel'
        }),
    },
    methods: {
        performMassAction (action) {
            new Function('value', `${action}(value)`)(this.selected)
        }
    },
}
</script>

<style lang="scss">
.es-list-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;

    .v-select {
        flex: 0 auto;
        width: 200px;
    }
}
</style>
