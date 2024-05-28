<template>
    <div class="es-list-filter-row">
        <div class="es-list-filter-row" style="width:100%">
            <v-autocomplete
                class="col col-3"
                v-model="row.field"
                @change="handleFieldChange"
                :filter="fieldsAutocompleteFilter"
                :items="filterableFields"
                item-value="name"
                item-text="label"
                :label="label('LBL_ESLIST_FIELD')"
                :no-data-text="label('LBL_ESLIST_NO_DATA')"
                dense
                outlined
                hide-details
            />
            <v-select
                class="col col-3"
                v-if="row.field"
                v-model="row.operator"
                @change="handleOperatorChange"
                :items="operatorItems"
                item-value="key"
                item-text="label"
                :label="label('LBL_ESLIST_OPERATOR')"
                dense
                outlined
                hide-details
            />
            <component
                v-for="input in row.inputs"
                :key="input"
                :fieldDefs="fieldDefs"
                :input="input"
                :is="getInputComponent(input.type)"
            />
        </div>
        <v-icon @click="$emit('delete-filter-row', row)" class="ms-auto">mdi-close</v-icon>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { standardizeText } from '../helpers'
import * as operatorDefs from '../operators'
import * as inputDefs from './es-list-filter-input'

export default {
    props: {
        row: { type: Object, required: true }
    },
    computed: {
        ...mapState({
            fieldDefs(state) {
                return this.row.field ? state.defs.search[this.row.field] : {}
            },
        }),
        ...mapGetters({
            label: 'getLabel',
            filterableFields: 'filterableFields',
        }),
        operatorList() {
            if (!this.row.field) {
                return {}
            }
            const type = this.fieldDefs.type
            return operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
        },
        operatorItems() {
            return Object.entries(this.operatorList).map(([key, op]) => ({ key, label: this.label(op.label) }))
        },
    },
    methods: {
        handleFieldChange() {
            this.row.operator = null
            this.row.inputs = []
        },
        handleOperatorChange() {
            if (!this.row.operator || !this.operatorList[this.row.operator].inputs) {
                this.row.inputs = [] // todo: czyscic value tylko roznych typow, albo jak zniknie
            } else {
                this.row.inputs = this.operatorList[this.row.operator].inputs.map(i => ({
                    type: i.type,
                    value: null,
                    label: this.label(i.label),
                }))
            }
        },
        fieldsAutocompleteFilter (field, searchText) {
            return standardizeText(field.label).includes(standardizeText(searchText))
        },
        getInputComponent(type) {
            return inputDefs[type] ? inputDefs[type] : null
        },
    },
}
</script>

<style lang="scss">
.es-list-filter-row {
    display: flex;
    gap: 16px;
}
</style>
