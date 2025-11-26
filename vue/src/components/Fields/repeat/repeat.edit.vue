<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{ fieldValue }}</div>
            <MintButton
                @click="startEdit()"
                icon="mdi-pencil"
                size="small"
                variant="nav"
                class="ml-auto"
                v-if="canEdit"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import MintButton from '../../MintButtons/MintButton.vue'
import { usePopupsStore } from '@/store/popups'
import { FieldProps } from '../Field.model'
import { useLanguagesStore } from '@/store/languages'
import { computed, ref, onMounted } from 'vue'
import MintPopupRepeat from '@/components/MintPopups/MintPopupRepeat.vue'
import { cyclicRecordsApi } from '@/api/cyclicRecords.api'

const props = defineProps<FieldProps>()
const popups = usePopupsStore()
const languages = useLanguagesStore()

const fieldValue = computed(() => {
    const repeatTypeDom = languages.getList('repeat_type_dom')
    const repeatIntervals = languages.getList('repeat_intervals')
    const calendarDays = languages.getList('dom_cal_day_long').filter((item) => item.value !== '')

    const repeatType = repeatTypeDom.find((item) => item.key === props.data.bean.attributes.repeat_type)?.value || ''
    const repeatInterval = repeatIntervals.find((item) => item.key == props.data.bean.attributes.repeat_type)?.value || ''
    
    if (props.data.bean.attributes.repeat_type == '') {
        return repeatType
    }

    let value = `${repeatType}, ${languages.label('LBL_EVERY')} ${props.data.bean.attributes.repeat_interval} ${repeatInterval}, `

    if (props.data.bean.attributes.repeat_count && props.data.bean.attributes.repeat_count !== '') {
        value += `${props.data.bean.attributes.repeat_count} ${languages.label('LBL_TIMES').toLowerCase()}`
    } else if (props.data.bean.attributes.repeat_until && props.data.bean.attributes.repeat_until !== '') {
        value += `${languages.label('LBL_UNTIL').toLowerCase()} ${props.data.bean.attributes.repeat_until}`
    }

    if (props.data.bean.attributes.repeat_type === 'Weekly' && props.data.bean.attributes.repeat_dow) {
        const days = props.data.bean.attributes.repeat_dow.split('').map((dayKey) => {
            return calendarDays.find((day) => day.key == dayKey)?.value || ''
        })
        value += `, ${languages.label('LBL_CRON_ON_THE_WEEKDAY')} ${days.join(', ')}`
    }

    return value
})

const canEdit = ref(false)

function startEdit() {
    popups.showPopup({
        title: languages.label('LBL_REPEAT_FIELD'),
        component: MintPopupRepeat,
        unclosable: false,
        data: {
            bean: props.data.bean,
        },
    })
}

onMounted(async () => {
    if (!props.data.bean.id) {
        canEdit.value = true
        return
    }
    
    const response = await cyclicRecordsApi.canEditRepeat(props.data.bean.module, props.data.bean.id)
    if (response.data.canEdit !== undefined && response.data.canEdit == true) {
        canEdit.value = true
    } else {
        canEdit.value = false
    }
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
