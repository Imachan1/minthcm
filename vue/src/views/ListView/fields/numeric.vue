<template>
    <v-text-field
        v-model="value"
        @input="handleInput"
        @keyup.enter="handleKeyEnter"
        variant="outlined"
        hide-details
        :label="input.label"
    />
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue'

const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input'])
const value = ref(props.input?.value)
const debounceTimeout = ref<number | null>(null)

function convertValue(v: string) {
    return parseFloat(v)
}

function handleInput() {
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value)
    }
    debounceTimeout.value = setTimeout(() => {
        emit('update:modelValue', convertValue(value.value))
    }, 1000)
}

function handleKeyEnter() {
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value)
    }
    emit('update:modelValue', convertValue(value.value))
}
</script>

<style scoped lang="scss"></style>
