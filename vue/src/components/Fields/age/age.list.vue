<template>
    <span>
        {{ parsedDate }}
        <span v-if="props.modelValue"> - ({{ age }} {{ languages.label('LBL_YEARS')?.toLowerCase() }})</span>
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences'
import { FieldProps } from '../Field.model'
import { useLanguagesStore } from '@/store/languages';

const props = defineProps<FieldProps>()
const preferences = usePreferencesStore()
const languages = useLanguagesStore()

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
const age = computed(() => {
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const birthDate = DateTime.fromSQL(value)
    const age = birthDate.diffNow('years').years
    return Math.floor(-age)
})
</script>

<style scoped lang="scss"></style>
