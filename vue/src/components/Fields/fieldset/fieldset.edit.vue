<template>
    <div class="fields-container">
        <div v-for="(field, i) in props.defs.properties?.fields" :key="field.name">
            <Field
                v-if="field && !store.bean.logic.hiddenFields.includes(field.name)"
                :view="store.bean.logic.readonlyFields.includes(field.name) ? 'detail' : store.view"
                :defs="field"
                :data="{ bean: store.bean.attributes }"
                :label="languages.label(field.label, modules.currentModule?.name)"
                :required="store.bean.logic.requiredFields.includes(field.name)"
                :errorMessage="store.bean.logic.errorMessages[field.name]"
                :isDirty="store.bean.isDirty || store.bean.dirtyFields.has(field.name)"
                :modelValue="store.bean[store.view === 'detail' ? 'syncAttributes' : 'attributes'][field.name]"
                @update:modelValue="(value, additionalFields) => store.updateField(field.name, value, additionalFields)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import { FieldVardef, useModulesStore } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import Field from '@/components/Fields/Field.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()

const store = useRecordViewStore()
const languages = useLanguagesStore()
const modules = useModulesStore()
</script>

<style scoped lang="scss">
.fields-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 24px;
    padding-left: 16px;
    border-left: 1px solid rgb(var(--v-theme-primary-light));
}
</style>
