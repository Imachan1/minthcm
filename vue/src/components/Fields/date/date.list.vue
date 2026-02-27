<template>
    <span :name="props.defs.name">{{ parsedDate }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model';
import { MintDate } from '@/composables/useMintDate';

const props = defineProps<FieldProps<MintDate>>()

const parsedDate = computed(() => {
    return props.field.model.isValid ? props.field.formatted.user : ''
})
</script>

<style scoped lang="scss"></style>
