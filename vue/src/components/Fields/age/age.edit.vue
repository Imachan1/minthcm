<template>
    <div class="mint-date-field-detail">
        <v-text-field
            v-bind="props"
            :label="label"
            variant="outlined"
            density="compact"
            hide-details
            :error="props.state === 'error'"
            v-model="parsedValue"
        />
        <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
            <template v-slot:activator="{ props, isActive }">
                <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
            </template>
            <v-date-picker v-model="pickerValue" hide-actions>
                <template #header></template>
            </v-date-picker>
        </v-menu>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { DateTime } from 'luxon'
import { FieldProps } from '../Field.model'
import { usePreferencesStore } from '@/store/preferences'
import { MintDate, useMintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()
const emit = defineEmits(['update:modelValue'])

const datePickerMenu = ref(false)
const model = ref(props.modelValue)
const preferences = usePreferencesStore()

const parsedValue = computed({
    get() {
        if (props.field.model.isValid) {
            return props.field.model.formatted.user_date
        }
        return ''
    },
    set(newVal) {
        datePickerMenu.value = false
        if (!newVal?.trim()) {
            props.field.model = useMintDate('')
            model.value = ''
        }
        const dt = DateTime.fromFormat(newVal, preferences.user?.date_format || 'dd.MM.yyyy', {
            zone: 'utc',
        })
        if (dt.isValid) {
            props.field.model.set(dt)
        }
    },
})
const pickerValue = computed({
    get() {
        if (!props.field.model) return new Date()
        return props.field.model.isValid ? props.field.model.formatted.js_date : new Date()
    },
    set(newVal) {
        props.field.model.set(newVal)
        model.value = props.field.formatted.server
    },
})

watch(model, (newVal) => {
    datePickerMenu.value = false
    const dt = DateTime.fromSQL(newVal?.toString())
    if (dt.isValid) {
        emit('update:modelValue', model.value)
    } else {
        emit('update:modelValue', '')
    }
})
</script>

<style scoped lang="scss">
.mint-date-field-detail {
    display: flex;
    gap: 16px;
    align-items: center;

    .mint-date-field-btn {
        transition: all 100ms ease-out;
        cursor: pointer;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        &:hover {
            color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
        }
    }
}
</style>
