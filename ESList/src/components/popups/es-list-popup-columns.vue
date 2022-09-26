<template>
    <ESListPopup
        :title="label('LBL_COLUMNS_MANAGEMENT')"
        @close-popup="$emit('close-popup')"
        :style="{
            minWidth: '700px'
        }"
    >
        <div class="es-list-columns">
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
                        :key="col"
                        v-text="label(columns[col].label)"
                        class="es-list-column-chip"
                        style="background: #ddd; color: black"
                        draggable
                        @dragstart="startDrag($event, col)"
                    />
                </div>
            </div>
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
                        :key="col"
                        v-text="label(columns[col].label)"
                        class="es-list-column-chip"
                        style="background: #009976; color: white"
                        draggable
                        @dragstart="startDrag($event, col)"
                    />
                </div>
            </div>
        </div>
        <v-divider class="mt-8" />
        <div class="es-list-columns-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined color="#009976" v-text="label('LBL_CANCEL')" />
            <v-btn @click="applyColumns" dark color="#009976" v-text="label('LBL_SAVE')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import ESListPopup from './es-list-popup'

export default {
    components: {
        ESListPopup
    },
    data: () => ({
        visibleColumns: []
    }),
    computed: {
        ...mapState({
            columns: (state) => state.columns
        }),
        ...mapGetters({
            label: 'getLabel',
            headers: 'headers'
        }),
        allColumns() {
            return Object.keys(this.columns)
        },
        hiddenColumns() {
            return this.allColumns.filter(col => !this.visibleColumns.includes(col))
        }
    },
    mounted () {
        this.visibleColumns = this.allColumns.filter(col => this.headers.find(h => h.value === col))
    },
    methods: {
        startDrag(e, col) {
            e.dataTransfer.dropEffect = 'move'
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('col', col)
        },
        onDrop(e, list) {
            const col = e.dataTransfer.getData('col')
            if (list === 'hidden-columns' && this.visibleColumns.includes(col)) {
                this.visibleColumns = this.visibleColumns.filter(c => c !== col)
            } else if (list === 'visible-columns' && !this.visibleColumns.includes(col)) {
                this.visibleColumns.push(col)
            }
        },
        applyColumns() {
            const columns = {}
            this.visibleColumns.forEach(key => {
                columns[key] = this.columns[key]
            })
            this.$store.commit('setUserColumns', columns)
            this.$emit('close-popup')
        }
    }
}
</script>

<style>
.es-list-columns {
    display: flex;
    gap: 16px;
}
.es-list-column {
    width: 100%;
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
    height: 300px;
    overflow: auto;
}
.es-list-columns-buttons {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
}
</style>
