<template>
    <ESListPopup
        :title="label('LBL_ESLIST_COLUMNS_MANAGEMENT')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '700px' }"
    >
        <div class="es-list-columns">
            <div class="es-list-column">
                <span v-text="label('LBL_ESLIST_VISIBLE_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent="onVisibleColumnsDragOver"
                    @dragenter.prevent
                    @drop="onDrop($event, 'visible-columns')"
                >
                    <div
                        v-for="col in visibleColumns"
                        :key="col.name"
                        class="es-list-column-chip visible"
                        :class="{ 'dragged': col.name === draggedColumnName }"
                        draggable
                        @dragstart="startDrag($event, col.name)"
                    >
                        <span v-text="col.label" />
                        <v-btn @click="moveColumnToHidden(col.name)" x-small icon :color="$store.state.config.theme.color['popup-column-visible--text']">
                            <v-icon>mdi-minus</v-icon>
                        </v-btn>
                    </div>
                </div>
            </div>
            <div class="es-list-column">
                <span v-text="label('LBL_ESLIST_HIDDEN_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent
                    @dragenter.prevent="onHiddenColumnsDragEnter"
                    @drop="onDrop($event, 'hidden-columns')"
                >
                    <div
                        v-for="col in hiddenColumns"
                        :key="col.name"
                        class="es-list-column-chip"
                        :class="{ 'dragged': col.name === draggedColumnName }"
                        draggable
                        @dragstart="startDrag($event, col.name)"
                    >
                        <span v-text="col.label" />
                        <v-btn @click="moveColumnToVisible(col.name)" x-small icon :color="$store.state.config.theme.color['popup-column-hidden--text']">
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </div>
                </div>
                <v-text-field
                    ref="filterInput"
                    v-model="columnsSearchPhrase"
                    class="mt-4"
                    dense
                    outlined
                    :label="label('LBL_ESLIST_FILTER')"
                />
            </div>
        </div>
        <v-divider class="mt-4" />
        <div class="es-list-columns-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined v-text="label('LBL_ESLIST_CANCEL')" />
            <v-btn @click="setDefaultColumns" outlined class="ms-auto" v-text="label('LBL_ESLIST_DEFAULT')" />
            <v-btn @click="applyColumns" color="primary" v-text="label('LBL_ESLIST_SAVE')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import { standardizeText } from '../../helpers'
import ESListPopup from './es-list-popup'

export default {
    components: { ESListPopup },
    data: () => ({
        visibleColumns: [],
        columnsSearchPhrase: '',
        draggedColumnName: null,
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
        onVisibleColumnsDragOver(e) {
            this.moveColumnToVisible(this.draggedColumnName)
            const path = e.composedPath()
            if (path[0] && path[0].classList.contains('es-list-column-chip') && !path[0].classList.contains('dragged')) {
                this.moveColumnToHidden(this.draggedColumnName)
                const index = [...path[0].parentNode.children].indexOf(path[0])
                this.visibleColumns.splice(index, 0, this.allColumns.find(c => c.name === this.draggedColumnName))
            }
        },
        onHiddenColumnsDragEnter() {
            this.moveColumnToHidden(this.draggedColumnName)
        },
        startDrag(e, colName) {
            e.dataTransfer.dropEffect = 'move'
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('colName', colName)
            this.draggedColumnName = colName
        },
        onDrop(e, list) {
            this.draggedColumnName = null
            const colName = e.dataTransfer.getData('colName')
            if (list === 'hidden-columns') {
                this.moveColumnToHidden(colName)
            } else if (list === 'visible-columns') {
                this.moveColumnToVisible(colName)
            }
        },
        moveColumnToHidden(colName) {
            this.visibleColumns = this.visibleColumns.filter(c => c.name !== colName)
        },
        moveColumnToVisible(colName) {
            if (!this.visibleColumns.find(c => c.name === colName)) {
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 100px;
    background: var(--color-popup-column-hidden--bg);
    color: var(--color-popup-column-hidden--text);
    padding: 4px 12px 4px 16px;
    cursor: grab;
    user-select: none;
    box-shadow: 0 1px 3px #0003;
    transform: translate(0, 0); /* trick to get rid of white corners during drag */
    opacity: .92;
    transition: opacity 200ms;

    &:hover {
        opacity: 1;
    }
    &.visible {
        background: var(--color-popup-column-visible--bg);
        color: var(--color-popup-column-visible--text);
    }
    &.dragged {
        opacity: .5;
    }
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

    &::-webkit-scrollbar {
        width: 5px;
        height: 8px;
        background: var(--color-scroll--bg);
    }
    &::-webkit-scrollbar-thumb {
        background: var(--color-scroll--fg);
    }
}
.es-list-columns-buttons {
    display: flex;
    gap: 16px;
    justify-content: space-between;
}
</style>
