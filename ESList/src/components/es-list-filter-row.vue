<template>
    <div class="es-list-filter-row">
        <v-autocomplete
            v-model="field"
            :items="fieldsItems"
            dense
            :label="label('LBL_FIELD')"
            outlined
            hide-details
        />
        <v-select
            v-if="field"
            v-model="operator"
            :items="operatorItems"
            dense
            :label="label('LBL_OPERATOR')"
            outlined
            hide-details
            item-text="label"
            item-value="key"
        />
        <component
            v-for="input in valueInputs"
            :key="input.id"
            :fieldDefs="fieldDefs"
            :input="input"
            :is="getInputComponent(input.type)"
        />
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import * as operatorDefs from '../operators'
import * as inputDefs from './es-list-filter-input'

export default {
    props: {
        filter: { type: Object, required: true }
    },
    data() {
        return {
            field: this.filter.field,
            operator: this.filter.op,
            value: null,
        }
    },
    computed: {
        ...mapState({
            fieldsItems(state) {
                return Object.values(state.search).map(x => ({ value: x.name, text: this.label(x.label) }))
            },
            fieldDefs(state) {
                return this.field ? state.search[this.field] : {}
            },
            operators(state) {
                if (!this.field) {
                    return {}
                }
                const type = state.search[this.field]?.type
                return operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
            },
        }),
        ...mapGetters({
            label: 'getLabel'
        }),
        operatorItems() {
            return Object.entries(this.operators).map(([key, op]) => ({ key, label: this.label(op.label) }))
        },
        valueInputs() {
            if (!this.operator) {
                return []
            }
            let id = new Date().getTime()
            return [
                ...(this.operators[this.operator]?.inputs ?? [])
            ].map(x => ({...x, id: ++id}))
        }
    },
    methods: {
        getList(list) {
            return Object.entries(
                SUGAR.language.languages['app_list_strings'][list] ?? {}
            ).map(([value, text]) => ({ value, text }))
        },
        getQSL() {
            const op = operatorDefs[this.fieldDefs.type]?.[this.operator]
            return op.filters.map(f => ({
                [f.op]: {
                    [this.fieldDefs.key]: f.value
                }
            }))
        },
        isValid() {
            if (!this.field || !this.operator) {
                return false
            }
            return true
        },
        getInputComponent(type) {
            if (inputDefs[type]) {
                return inputDefs[type]
            }
            return null
        }
    },
    watch: {
        field() {
            this.operator = null
            this.value = null
            this.$emit('filter-changed')
        },
        operator() {
            this.$emit('filter-changed')
        }
    }
}
</script>

<style lang="scss">
.es-list-filter-row {
    display: flex;
    gap: 16px;

    .v-select {
        flex: 0 auto;
        width: 200px;
    }
}
</style>
