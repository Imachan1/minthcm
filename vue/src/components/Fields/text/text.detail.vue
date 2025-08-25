<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <div>
                {{ value }}
                <a v-if="props.modelValue?.length > lengthToCrop" @click="expanded = !expanded"
                    >{{ languages.label(expanded ? 'LBL_COLLAPSE' : 'LBL_EXPAND') }}
                    <v-icon :icon="expanded ? 'mdi-chevron-up' : 'mdi-chevron-down'" />
                </a>
            </div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
    hidePencil?: boolean
}

const props = defineProps<Props>()
const emit = defineEmits(['inlineEditBtnClicked'])
const languages = useLanguagesStore()

const lengthToCrop = 180
const expanded = ref<boolean>(false)

const value = computed(() =>
    !expanded.value && props.modelValue?.length > lengthToCrop
        ? props.modelValue.substring(0, lengthToCrop).trim() + '...'
        : props.modelValue,
)
function startInlineEdit() {
    if (props?.defs?.name && typeof props.defs.name === 'string' && props.defs.name.length > 0) {
        emit('inlineEditBtnClicked', props.defs.name)
    }
}
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
a {
    color: rgb(var(--v-theme-secondary));
    display: block;
    &:hover {
        cursor: pointer;
    }
}
</style>
