<template>
    <span>{{ parsedDate }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences';
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const preferences = usePreferencesStore()

const parsedDate = computed(() => {
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const dt = DateTime.fromSQL(value)
    if (!dt.isValid) {
        return ''
    }
    return dt.toFormat(preferences.user?.date_format || 'dd.MM.yyyy')
})
</script>

<style scoped lang="scss"></style>
