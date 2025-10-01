<template>
    <div>
        <v-chip
            v-if="props.defs?.options_colors"
            :style="coloredEnumStyle"
            class="enum-chip"
        >
            {{ languages.translateListValue(props.data.bean[props.defs.name], props.defs?.options) }}
        </v-chip>
        <div v-else>{{ languages.translateListValue(props.data.bean[props.defs.name], props.defs?.options) }}</div>
    </div>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import { computed, defineProps } from 'vue'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
const backend = useBackendStore()

const coloredEnumStyle = computed(() => {
    const colors = backend.initData.field_variables?.ColoredEnum?.options_colors
    if (colors && props.defs?.options_colors) {
        return colors[props.defs.options_colors[props.data.bean[props.defs.name]]] || colors['-default-']
    }
    return ''
})
</script>

<style scoped lang="scss">
:deep(.v-chip.v-chip--size-default) {
    height: 28px;
    border-radius: 4px;
    letter-spacing: 0.09px;
}
.enum-chip {
    display: flex;
    align-items: center;
    justify-content: center;
    width: fit-content;
    font-size: 13px;
    padding: 4px 12px;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 5px;
    letter-spacing: 0.09px;
}
</style>
