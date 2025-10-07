<template>
    <div class="mass-update-row">
        <v-row no-gutters>
            <v-col cols="2" class="px-2">
                <v-autocomplete
                    class="col col-2"
                    v-model="field"
                    @update:model-value="handleFieldChange"
                    :items="store.massUpdatableFields"
                    item-value="name"
                    item-title="label"
                    :label="languages.label('LBL_ESLIST_FIELD')"
                    :no-data-text="languages.label('LBL_ESLIST_NO_DATA')"
                    variant="outlined"
                    hide-details
                    density="compact"
                />
            </v-col>
            <v-col cols="2" v-for="input in inputs" :key="input" class="px-2">
                <component
                    :is="getInputComponent(input.type)"
                    :fieldDefs="fieldDefs"
                    :input="input"
                    density="compact"
                    @update:modelValue="(newValue) => (input.value = newValue)"
                />
            </v-col>
        </v-row>
        <v-btn
            class="ms-auto"
            variant="text"
            density="comfortable"
            icon="mdi-close"
            @click="store.deleteMassUpdateRow(props.index)"
        />
    </div>
</template>

<script setup lang="ts">
import { useListViewStore } from './ListViewStore'
import { defineProps, ref, defineEmits, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import * as fieldsDefs from './fields'
import * as inputDefs from './inputs'

export interface MassUpdateRow {
    field: string | null
    value?: any
    inputs: []
}

interface Props {
    row: MassUpdateRow
    index: number
}

const props = defineProps<Props>()

const store = useListViewStore()
const languages = useLanguagesStore()
const emit = defineEmits(['update:field', 'update:inputs'])

const field = ref(props.row.field ?? '')
const inputs = ref(props.row.inputs ?? [])
const fieldDefs = computed(() => {
    return field.value ? store.defs?.massupdate?.[field.value] : {}
})

const inputsMap = computed(() => {
    if (!field.value) return {}
    const type = fieldDefs.value.type
    return inputDefs[type] ?? inputDefs[inputDefs.typeMap[type]] ?? inputDefs[inputDefs.defaultInput]
})

function handleFieldChange() {
    inputs.value = [{
        type: inputsMap.value.type,
        label: languages.label(inputsMap.value.label)
    }]
    emit('update:field', field.value)
    emit('update:inputs', inputs.value)
}

function getInputComponent(type: string) {
    return fieldsDefs[type] ? fieldsDefs[type] : null
}

</script>
<style scoped lang="scss">
.mass-update-row {
    margin: 0 10px;
    padding: 10px 0 10px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-bottom: 1px solid #0000001f;
}

.mass-update-row:first-child {
    border-top: 1px solid #0000001f;
}
</style>