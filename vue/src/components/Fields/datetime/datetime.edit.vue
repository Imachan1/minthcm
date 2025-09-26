<template>
    <div class="mint-date-field-detail" @keyup.enter="$emit('inlineEditSave')" @keyup.esc="$emit('inlineEditCancel')">
        <v-text-field
            :label="label"
            variant="outlined"
            density="compact"
            hide-details
            v-model="dateValue"
            :error="props.state === 'error'"
        >
            <template #append-inner>
                <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
                    </template>
                    <v-date-picker v-model="datePickerValue" hide-actions>
                        <template #header></template>
                    </v-date-picker>
                </v-menu>
            </template>
        </v-text-field>
        <v-text-field :disabled="!dateValue" variant="outlined" density="compact" hide-details v-model="timeValue">
            <template #append-inner>
                <v-menu v-model="timePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-clock-time-eight-outline</v-icon>
                    </template>
                    <v-time-picker
                        v-model="timePickerValue"
                        :format="timeFormat"
                        :ampm-in-title="timeFormat === 'ampm'"
                    >
                        <template #header></template>
                    </v-time-picker>
                </v-menu>
            </template>
        </v-text-field>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { DateTime } from 'luxon'
import { FieldProps } from '../Field.model'
import { usePreferencesStore } from '@/store/preferences'
import DateUtils from '@/utils/dates'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])

const datePickerMenu = ref(false)
const timePickerMenu = ref(false)
const model = ref(props.modelValue)
const preferences = usePreferencesStore()

const timeFormat = computed(() => {
    return DateUtils.getTimeFormatGeneralized()
})

const dateValue = computed({
    get() {
        const dt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (dt.isValid) {
            return dt.setZone('Europe/Warsaw').toFormat(preferences.user?.date_format || 'yyyy-MM-dd') || ''
        }
        return ''
    },
    set(newVal) {
        datePickerMenu.value = false
        if (!newVal?.trim()) {
            model.value = ''
        }
        const dt = DateTime.fromFormat(newVal, preferences.user?.date_format || 'yyyy-MM-dd')
        if (dt.isValid) {
            model.value = dt.toSQLDate()
        }
    },
})

const timeValue = computed({
    get() {
        const dt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (dt.isValid) {
            return dt.toLocal().toFormat('HH:mm') || '00:00'
        }
        return '00:00'
    },
    set(newVal) {
        timePickerMenu.value = false
        newVal = DateTime.fromSQL(newVal).setZone('UTC').toFormat('HH:mm')
        const modelDt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (modelDt.isValid) {
            model.value = `${modelDt.toFormat('yyyy-MM-dd')} ${newVal}:00`
        } else {
            model.value = ''
        }
    },
})

const datePickerValue = computed({
    get() {
        if (!model.value?.trim()) {
            return new Date()
        }
        return new Date(model.value)
    },
    set(newVal) {
        const dt = DateTime.fromJSDate(newVal)
        if (dt.isValid) {
            const modelDt = DateTime.fromSQL(model.value)
            if (!modelDt.isValid) {
                model.value = dt.toFormat('yyyy-MM-dd HH:mm:ss')
            } else {
                model.value = `${dt.toFormat('yyyy-MM-dd')} ${modelDt.toFormat('HH:mm:ss')}`
            }
        }
    },
})

const timePickerValue = computed({
    get() {
        const dt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (dt.isValid) {
            return dt.toLocal().toFormat('HH:mm') || '00:00'
        }
        return '00:00'
    },
    set(newVal) {
        newVal = DateTime.fromFormat(newVal, 'HH:mm').setZone('UTC').toFormat('HH:mm')
        const modelDt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (modelDt.isValid) {
            model.value = `${modelDt.toFormat('yyyy-MM-dd')} ${newVal}:00`
        } else {
            model.value = ''
        }
    },
})

watch(model, (newVal) => {
    datePickerMenu.value = false
    timePickerMenu.value = false
    const dt = DateTime.fromSQL(newVal?.toString())
    if (dt.isValid) {
        emit('update:modelValue', model.value)
    } else {
        emit('update:modelValue', '')
    }
})
watch(
    () => props.modelValue,
    () => {
        model.value = props.modelValue
    },
)
</script>

<style scoped lang="scss">
.mint-date-field-detail {
    display: flex;
    gap: 16px;
    align-items: center;

    .mint-date-field-btn {
        transition: all 100ms ease-out;
        cursor: pointer;
        &:hover {
            color: rgb(var(--v-theme-on-surface));
        }
    }
}
</style>
