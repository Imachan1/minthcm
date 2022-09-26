<template>
    <div>
        <v-scale-transition origin="center center 0">
            <ESListPopupColumns
                v-if="columnsPopupVisible"
                @close-popup="columnsPopupVisible = false"
            />
        </v-scale-transition>
        <div class="es-list-header">
            <v-select
                @change="null"
                :value="null"
                :items="[]"
                dense
                class=""
                :label="label('LBL_CHOOSE_MULTIPLE')"
                outlined
                append-icon="mdi-chevron-down"
                hide-details
            />
            <v-select
                v-show="true"
                @change="null"
                :value="null"
                :items="[]"
                dense
                class=""
                :label="label('LBL_MASS_ACTION')"
                outlined
                append-icon="mdi-chevron-down"
                hide-details
            />
            <v-btn
                small
                class="ms-auto"
                color="#009976"
                outlined
                @click="columnsPopupVisible = true"
            >
                <v-icon left>mdi-playlist-plus</v-icon>
                {{ label('LBL_COLUMNS') }}
            </v-btn>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ESListPopupColumns from './popups/es-list-popup-columns'

export default {
    components: { ESListPopupColumns },
    data: () => ({
        columnsPopupVisible: false
    }),
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    }
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
