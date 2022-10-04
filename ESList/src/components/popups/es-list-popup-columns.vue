<template>
    <ESListPopup
        :title="label('LBL_COLUMNS_MANAGEMENT')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '700px' }"
    >
        <div class="es-list-columns">
            <div class="es-list-column">
                <span v-text="label('LBL_VISIBLE_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent
                    @dragenter.prevent
                    @drop="onDrop($event, 'visible-columns')"
                >
                    <div
                        v-for="col in visibleColumns"
                        :key="col.name"
                        v-text="col.label"
                        class="es-list-column-chip"
                        style="background: #009976; color: white"
                        draggable
                        @dragstart="startDrag($event, col.name)"
                    />
                </div>
            </div>
            <div class="es-list-column">
                <span v-text="label('LBL_HIDDEN_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent
                    @dragenter.prevent
                    @drop="onDrop($event, 'hidden-columns')"
                >
                    <div
                        v-for="col in hiddenColumns"
                        :key="col.name"
                        v-text="col.label"
                        class="es-list-column-chip"
                        style="background: #ddd; color: black"
                        draggable
                        @dragstart="startDrag($event, col.name)"
                    />
                </div>
                <v-text-field
                    ref="filterInput"
                    v-model="columnsSearchPhrase"
                    class="mt-4"
                    dense
                    outlined
                    :label="label('LBL_FILTER')"
                />
            </div>
        </div>
        <v-divider class="mt-4" />
        <div class="es-list-columns-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined color="#009976" v-text="label('LBL_CANCEL')" />
            <v-btn @click="setDefaultColumns" outlined color="#009976" class="ms-auto" v-text="label('LBL_DEFAULT')" />
            <v-btn @click="applyColumns" dark color="#009976" v-text="label('LBL_SAVE')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import { standardizeText } from '../../helpers'
import ESListPopup from './es-list-popup'

export default {
    components: {
        ESListPopup
    },
    data: () => ({
        visibleColumns: [],
        columnsSearchPhrase: '',
    }),
    computed: {
        ...mapGetters({
            label: 'getLabel',
            allColumns: 'allColumns',
        }),
        standardizedColumnsSearchPhrase() {
            return standardizeText(this.columnsSearchPhrase)
        },
        hiddenColumns() {
            return this.allColumns
                .filter(col => !this.visibleColumns.find(c => c.name === col.name))
                .filter(col => (!this.standardizedColumnsSearchPhrase || standardizeText(col.label).includes(this.standardizedColumnsSearchPhrase)))
        }
    },
    mounted () {
        this.visibleColumns = [...this.$store.getters['visibleColumns']]
        this.$refs.filterInput.focus()
    },
    methods: {
        startDrag(e, colName) {
            e.dataTransfer.dropEffect = 'move'
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('colName', colName)
        },
        onDrop(e, list) {
            const colName = e.dataTransfer.getData('colName')
            if (list === 'hidden-columns') {
                this.visibleColumns = this.visibleColumns.filter(c => c.name !== colName)
            } else if (list === 'visible-columns' && !this.visibleColumns.find(c => c.name === colName)) {
                this.visibleColumns.push(this.allColumns.find(c => c.name === colName))
            }
        },
        applyColumns() {
            this.$store.commit('setColumnsPreference', this.visibleColumns.map(col => col.name))
            this.$store.dispatch('savePreferences')
            this.$emit('close-popup')
        },
        setDefaultColumns() {
            this.$store.commit('setDefaultColumns')
            this.$store.dispatch('savePreferences')
            this.$emit('close-popup')
        }
    }
}
</script>

<style lang="scss">
.es-list-columns {
    display: flex;
    gap: 16px;
}
.es-list-column {
    width: 100%;
    &>span {
        user-select: none;
    }
}
.es-list-column-chip {
    border-radius: 100px;
    background: #ddd;
    padding: 4px 16px;
    cursor: grab;
    user-select: none;
    box-shadow: 0 1px 3px #0003;
    transform: translate(0, 0); /* trick to get rid of white corners during drag */
}
.columns-container {
    border: thin solid #0003;
    border-radius: 2px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    box-shadow: 0 2px 6px #0003;
    height: 350px;
    overflow: auto;
}
.es-list-columns-buttons {
    display: flex;
    gap: 16px;
    justify-content: space-between;
}
</style>
