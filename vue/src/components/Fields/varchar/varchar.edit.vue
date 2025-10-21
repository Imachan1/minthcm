<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :name="props.defs.name"
        :modelValue="props.modelValue"
        :error="props.state === 'error'"
        @update:modelValue="(v) => $emit('update:modelValue', v)"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
        autocomplete="on"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss"></style>
