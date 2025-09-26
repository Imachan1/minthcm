<template>
    <div>
        <div class="enum-chip" v-if="props.defs?.options_colors"
            :style="languages.translateListValue(props.modelValue, props.defs?.options_colors)">
            {{ languages.translateListValue(props.modelValue, props.defs?.options) }}
        </div>
        <div v-else>{{ languages.translateListValue(props.modelValue, props.defs?.options) }}</div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
</script>

<style scoped lang="scss">
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
