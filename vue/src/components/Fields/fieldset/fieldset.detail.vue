<template>
    <label>{{ props.label }}</label>
    <div class="d-flex detail-field-container">
        <p v-html="fieldContent"></p>
        <Pencil :defs="props.defs" />
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const fieldContent = computed(() => {
    let text = ''
    props.defs.properties?.fields.forEach((field, index) => {
        text += props.data.bean[field.name]
        if (index !== props.defs.properties?.fields?.length - 1) {
            text += props.defs.properties?.separator
        }
    })
    return text
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
p {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
