<template>
    <v-select
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :error="props.state === 'error'"
        v-model="parsedValue"
        :items="languages.getList(props.defs?.options)"
        item-title="value"
        item-value="key"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    >
    </v-select>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const emit = defineEmits(['update:modelValue'])
const model = ref('')

const parsedValue = computed({
    get() {
        return languages.translateListValue(props.modelValue ?? props.defs?.default ?? '', props.defs?.options)
    },
    set(newValue) {
        model.value = newValue
        emit('update:modelValue', model.value)
    },
})
</script>

<style scoped lang="scss"></style>
