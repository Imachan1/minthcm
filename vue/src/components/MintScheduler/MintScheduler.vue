<template>
    <div class="scheduler-container">
        <div v-if="!scheduler.isInitialized.value" class="scheduler-loader">
            <v-progress-circular indeterminate color="primary" size="80" width="5" />
        </div>
        <div v-else-if="!scheduler.isValid.value">{{ language.label('LBL_SCHEDULER_INVALID') }}</div>
        <div v-else class="scheduler-content">
            <MintSchedulerTimeline :bean="props.bean" :scheduler="scheduler" />
            <MintSchedulerParticipants :scheduler="scheduler" />
            <MintSchedulerAssigner v-if="scheduler.isEditable.value" :scheduler="scheduler" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Ref, toValue, ref, watch } from 'vue'
import { useMintScheduler } from './useMintScheduler'
import { useBean } from '@/composables/useBean'
import MintSchedulerAssigner from './MintSchedulerAssigner.vue'
import MintSchedulerParticipants from './MintSchedulerParticipants.vue'
import MintSchedulerTimeline from './MintSchedulerTimeline.vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    bean: ReturnType<typeof useBean>
    dateFrom: Ref<string | null> | string
    dateTo: Ref<string | null> | string
}

const props = defineProps<Props>()

const language = useLanguagesStore()

const dateFromRef = ref(toValue(props.dateFrom))
const dateToRef = ref(toValue(props.dateTo))
watch(
    () => props.dateFrom,
    (newVal) => {
        dateFromRef.value = toValue(newVal)
    },
)
watch(
    () => props.dateTo,
    (newVal) => {
        dateToRef.value = toValue(newVal)
    },
)

const scheduler = useMintScheduler(props.bean, dateFromRef, dateToRef)
scheduler.init()
</script>

<style scoped lang="scss">
.scheduler-container {
    min-height: 200px;
    padding-top: 48px;
}

.left-col {
    width: 250px;
    min-width: 250px;
    max-width: 250px;
}

.scheduler-loader {
    width: 100%;
    height: 100%;
    opacity: 0.8;
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.scheduler-content {
    position: relative;
    display: flex;
    flex-direction: column;
}

.scheduler-search-bar {
    position: relative;
    padding: 32px 0px 16px 0px;
    width: 100%;
    background: rgb(var(--v-theme-background));
}
</style>
