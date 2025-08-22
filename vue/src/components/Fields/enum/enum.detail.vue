<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <v-chip
                v-if="props.defs?.options_colors"
                class="enum-chip"
                :color="languages.translateListValue(props.modelValue, props.defs?.options_colors)"
            >
                {{ parsedValue }}
            </v-chip>
            <div v-else>{{ parsedValue }}</div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import Pencil from '../Pencil.vue'
import { computed } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()

const parsedValue = computed(() => {
    return items.value.find((item) => item.key === props.modelValue)?.value || ''
})

const items = computed(() => {
    const options = props.options ?? props.defs?.options
    if (!options) {
        return []
    hidePencil?: boolean
}
    if (typeof options === 'string') {
        return languages.getList(options)
    }
    if (!Array.isArray(options) && typeof options === 'object') {
        return Object.entries(options).map(([key, value]) => ({
            key,
            value,
        }))
    }
    return options
})
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
    display: block;
}
:deep(.v-chip.v-chip--size-default) {
    height: 28px;
    border-radius: 4px;
    letter-spacing: 0.09px;
}
</style>
