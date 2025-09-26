<template>
    <div class="scheduler-panel">
        <div class="panel-title">
            <h1>{{ language.label('LBL_SCHEDULER_PANEL_TITLE') }}</h1>
        </div>
        <div class="panel-content">
            <MintScheduler
                :bean="store.bean"
                :dateFrom="store.bean.attributes.date_start"
                :dateTo="store.bean.attributes.date_end"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import MintScheduler from '@/components/MintScheduler/MintScheduler.vue'
import { useLanguagesStore } from '@/store/languages'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'

const store = useRecordViewStore()
const language = useLanguagesStore()
</script>

<style scoped lang="scss">
.scheduler-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;

    .panel-title {
        padding: 16px;
        border-bottom: 1px solid #dbdbdb;
        h1 {
            font-size: 20px;
        }
    }

    .panel-content {
        padding: 16px 0px 16px 16px;
        position: relative;
    }
}
</style>
