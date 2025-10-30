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
        <div>
            <v-expansion-panels multiple variant="accordion" class="details-accordion" v-model="expandedSections">
                <v-expansion-panel
                    v-for="(section, index) in props.data.sections"
                    :key="index"
                    :class="['mint-panel', section.fields.length <= 0 && 'mint-panel-disabled']"
                    :value="index"
                    bg-color="transparent"
                >
                    <v-expansion-panel-title class="mint-panel-title" hide-actions>
                        <div>
                            <v-icon
                                :icon="expandedSections.includes(index) ? 'mdi-chevron-down' : 'mdi-chevron-right'"
                            />
                            <span>{{
                                languages.label(section.title, modules.currentModule?.name) ??
                                languages.label(section.title) ??
                                section.title ??
                                ''
                            }}</span>
                        </div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text class="fields-container">
                        <div v-for="(row, i) in computeRows(section)" class="row" :key="row">
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
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted, watch } from 'vue'
import Field from '@/components/Fields/Field.vue'
import { FieldVardef } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useModulesStore } from '@/store/modules'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBoxes/MintStatusBox.vue'
import { useLocalStorageStore } from '@/store/localStorage'

interface Props {
    data: {
        sections: Array<{
            title: string
            collapsed?: boolean
            fields: Array<FieldVardef>[]
        }>
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const languages = useLanguagesStore()
const modules = useModulesStore()
const storage = useLocalStorageStore()

const storageInitialized = ref(false)
const expandedSections = computed({
    get: () => {
        storageInitialized.value = true
        return storage.getPanelSections(store.bean.module, 'MintPanelRecordDetails')
    },
    set: (value: string[]) => storage.setPanelSections(store.bean.module, 'MintPanelRecordDetails', value)
})


const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_DETAILS', modules.currentModule?.name)
})

const computeRows = (panel) => {
    return panel.fields.filter((row) => row.some((field) => !store.bean.logic.hiddenFields.includes(field.name)))
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

onMounted(() => {
    if (storageInitialized.value) {
        return
    }

    let array = []
    const keys = Object.keys(props.data.sections)
    for (let i = 0; i < keys.length; i++) {
        const key = keys[i]
        if (!props.data.sections[key].collapsed) {
            array.push(key)
        }
    }
    expandedSections.value = array
})

watch(() => [store.bean.errorMessages, store.view], ([newError, newView]) => {
    if (store.view === 'edit') {
        const errorFields = Object.keys(newError)
        const sectionsToExpand = [] as string[]
        Object.keys(props.data.sections).forEach((key) => {
            const section = props.data.sections[key]
            const sectionFieldNames = section.fields.flat().map((field) => field.name)
            if (sectionFieldNames.some((name) => errorFields.includes(name))) {
                sectionsToExpand.push(key)
            }
        })
        expandedSections.value = Array.from(new Set([...expandedSections.value, ...sectionsToExpand]))
    }
})
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
        display: flex;
        flex-direction: column;

        .row {
            display: flex;
            gap: 24px;

            > * {
                flex: 1;
            }
            margin-bottom: 24px;
        }
    }
}

.details-accordion {
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }
}

.mint-panel {
    .mint-panel-title {
        color: rgb(var(--v-theme-secondary));
        font-size: 15px;
        font-weight: 600;
        padding: 16px;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        padding: 12px 16px;

        > div {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    }
    .mint-panel-content {
        :deep(.v-expansion-panel-text__wrapper) {
            padding: 0px;
        }
    }
}
</style>