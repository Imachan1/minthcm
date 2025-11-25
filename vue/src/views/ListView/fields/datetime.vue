<template>
    <div class="mint-date-field-detail" @keyup.enter="$emit('inlineEditSave')" @keyup.esc="$emit('inlineEditCancel')">
        <v-text-field
            :label="dateLabel"
            variant="outlined"
            density="compact"
            hide-details
            v-model="dateValue"
            :error="!isValidDateTime"
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
        <v-text-field
            :disabled="!dateValue"
            variant="outlined"
            density="compact"
            hide-details
            v-model="timeValue"
            :label="timeLabel"
            :error="!isValidDateTime"
        >
            <template #append-inner>
                <v-menu v-model="timePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-clock-time-eight-outline</v-icon>
                    </template>
                    <v-time-picker
                        v-model="timeValue"
                        :format="timeFormat"
                        :ampm-in-title="timeFormat === 'ampm'"
                        :allowed-minutes="allowedMinutesStep"
                        scrollable
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
import { usePreferencesStore } from '@/store/preferences';
import DateUtils from '@/utils/dates'

const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input', 'disabled'])
const value = ref(props.input?.value)
const datePickerMenu = ref(false)
const timePickerMenu = ref(false)
const preferences = usePreferencesStore()
const dateLabel = ref(props.input.label[0])
const timeLabel = ref(props.input.label[1])
const isValidDateTime = computed(() => {
    return !value.value || value.value.length === 19
})

const allowedMinutesStep = (m: number) => m % 5 === 0

const timeFormat = computed(() => {
    return DateUtils.getTimeFormatGeneralized()
})

const dateValue = computed({
    get() {
        const dt = DateTime.fromSQL(value.value)
        if (dt.isValid) {
            return dt.toFormat(preferences.user?.date_format || 'yyyy-MM-dd') || ''
        }
        return ''
    },
    async set(newVal) {
        datePickerMenu.value = false
        const dt = DateTime.fromFormat(newVal, preferences.user?.date_format || 'yyyy-MM-dd')
        if (dt.isValid) {
            value.value = dt.toSQLDate()
        }
    },
})

const timeValue = computed({
    get() {
        const dt = DateTime.fromSQL(value.value, { zone: 'UTC' })
        if (dt.isValid) {
            return dt.toFormat('HH:mm') || '00:00'
        }
        return '00:00'
    },
    async set(newVal) {
        const timeDt = DateTime.fromFormat(newVal, 'HH:mm')
        if (!timeDt.isValid) {
            return
        }
        const modelDt = DateTime.fromSQL(value.value, { zone: 'UTC' })
        if (modelDt.isValid) {
            value.value = `${modelDt.toFormat('yyyy-MM-dd')} ${timeDt.toFormat('HH:mm')}:00`
        }
    },
})

const datePickerValue = computed({
    get() {
        if (!value.value?.trim()) {
            return new Date()
        }
        return new Date(value.value)
    },
    set(newVal) {
        const dt = DateTime.fromJSDate(newVal)
        if (dt.isValid) {
            const modelDt = DateTime.fromSQL(value.value)
            if (!modelDt.isValid) {
                value.value = dt.toFormat('yyyy-MM-dd HH:mm:ss')
            } else {
                value.value = `${dt.toFormat('yyyy-MM-dd')} ${modelDt.toFormat('HH:mm:ss')}`
            }
        }
    },
})

watch(value, (newVal) => {
    datePickerMenu.value = false
    const dt = DateTime.fromSQL(newVal?.toString())
    if (dt.isValid) {
        emit('update:modelValue', value.value)
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
        &:hover {
            color: rgb(var(--v-theme-on-surface));
        }
    }
}
</style>
