<template>
    <div class="details-panel">
        <div class="tabs-container">
            <h1>{{ title }}</h1>
            <MintStatusBox v-if="store.view === 'edit' && store.bean.validationError" type="error">
                {{ languages.label(store.bean.validationError, store.bean.module) }}
            </MintStatusBox>
            <div v-if="store.bean.aclAccess?.edit === true">
                <MintButton
                    v-if="store.view === 'detail'"
                    class="ml-auto"
                    icon="mdi-pencil"
                    :text="`${languages.label('LBL_EDIT_BUTTON_LABEL')} ${languages.label('LBL_DETAILS')}`"
                    @click="edit"
                />
                <div class="buttons" v-if="store.view === 'edit'">
                    <MintButton
                        v-if="!store.bean.isSaving && !store.bean.isNew"
                        icon="mdi-close"
                        :text="languages.label('LBL_CANCEL_BUTTON_LABEL')"
                        @click="cancel"
                    />
                    <MintButton
                        :disabled="!store.bean.isValid || store.bean.isSaving"
                        :icon="!store.bean.isSaving ? 'mdi-check' : ''"
                        :loading="store.bean.isSaving"
                        variant="primary"
                        :text="languages.label(store.bean.isSaving ? 'LBL_SAVING' : 'LBL_SAVE_BUTTON_LABEL')"
                        @click="save"
                    />
                </div>
            </div>
        </div>
        <div class="fields-container">
            <div v-for="(row, i) in rows" class="row" :key="row">
                <div v-for="n in store.columns" :key="n - 1">
                    <Field
                        v-if="row[n - 1] && !store.bean.logic.hiddenFields.includes(row[n - 1].name)"
                        :view="store.bean.logic.readonlyFields.includes(row[n - 1].name) ? 'detail' : store.view"
                        :defs="row[n - 1]"
                        :data="{ bean: store.bean }"
                        :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                        :options="store.bean.logic.fieldsOptions[row[n - 1].name]"
                        :required="store.bean.logic.requiredFields.includes(row[n - 1].name)"
                        :errorMessage="store.bean.errorMessages[row[n - 1].name]"
                        :isDirty="store.bean.isDirty || store.bean.dirtyFields.has(row[n - 1].name)"
                        :modelValue="
                            store.bean[store.view === 'detail' ? 'syncAttributes' : 'attributes'][row[n - 1].name]
                        "
                        @update:modelValue="
                            (value, additionalFields) => store.updateField(row[n - 1].name, value, additionalFields)
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Field from '@/components/Fields/Field.vue'
import { FieldVardef } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useModulesStore } from '@/store/modules'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'

interface Props {
    data: {
        fields: Array<Array<FieldVardef>>
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const languages = useLanguagesStore()
const modules = useModulesStore()

const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_DETAILS', modules.currentModule?.name)
})

const rows = computed(() => {
    return props.data.fields.filter((row) => row.some((field) => !store.bean.logic.hiddenFields.includes(field.name)))
})

const inlineEditBtnClicked = (event: string) => {
    store.inlineEditField = event
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
}

const edit = () => {
    store.view = 'edit'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
}

const cancel = () => {
    store.bean.restore()
    store.view = 'detail'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
}

const save = async () => {
    if (store.bean.isSaving) {
        return
    }
    const prevInlineEditField = store.inlineEditField
    if (prevInlineEditField) {
        store.inlineEditFieldSaving = prevInlineEditField
    }
    store.inlineEditField = ''
    const response = await store.bean.save()
    if (response) {
        store.view = 'detail'
        store.inlineEditField = ''
        store.inlineEditFieldSaving = ''
    } else {
        store.inlineEditField = prevInlineEditField
    }
}
</script>

<style scoped lang="scss">
.details-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;

    h1 {
        font-size: 20px;
    }

    .tabs-container {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #dbdbdb;

        .buttons {
            display: flex;
            gap: 16px;
            justify-content: end;
        }

        > * {
            flex: 1;
        }
    }

    .fields-container {
        padding: 24px;
        display: flex;
        gap: 24px;
        flex-direction: column;

        .row {
            display: flex;
            gap: 24px;

            > * {
                flex: 1;
            }
        }
    }
}
</style>
