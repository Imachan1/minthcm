<template>
    <div class="scheduler-row">
        <router-link :to="participantUrl" target="_blank" class="scheduler-participant">
            <MintAvatar
                :photo="props.participant.meta?.photo ? `${props.participant.id}_photo` : null"
                :fullName="props.participant.name"
            />
            <div class="scheduler-participant-name">
                <span>{{ props.participant.name }}</span>
                <span v-if="description" class="scheduler-participant-description">{{ description }}</span>
            </div>
        </router-link>
        <div class="scheduler-data">
            <MintSchedulerDataWorkschedule
                v-for="workschedule in participant.workschedules"
                :key="workschedule.id"
                :workschedule="workschedule"
                :scheduler="scheduler"
            />
            <MintSchedulerDataActivity
                v-for="activity in activities"
                :key="activity.id"
                :activity="activity"
                :scheduler="scheduler"
            />
        </div>
        <div class="scheduler-actions">
            <slot name="actions"></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import MintAvatar from '../MintAvatar.vue'
import MintSchedulerDataActivity from './MintSchedulerData/MintSchedulerDataActivity.vue'
import MintSchedulerDataWorkschedule from './MintSchedulerData/MintSchedulerDataWorkschedule.vue'
import { useMintScheduler } from './useMintScheduler'
import { DataActivity, Participant } from './MintScheduler.model'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    participant: Participant
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()

const languagesStore = useLanguagesStore()

const participantUrl = computed(() => {
    return `/modules/${props.participant.module}/DetailView/${props.participant.id}`
})

const activities = computed(() => {
    const activities = [] as DataActivity[]
    let prevActivity: DataActivity | null = null
    props.participant.activities
        ?.toSorted((a, b) => (a.date_start < b.date_start ? -1 : a.date_start > b.date_start ? 1 : 0))
        .forEach((activity: any) => {
            if (activity.id === props.scheduler.bean?.id) {
                return
            }
            const overflowsWithPrevious =
                prevActivity &&
                prevActivity.date_start < activity.date_end &&
                prevActivity.date_end > activity.date_start &&
                !prevActivity.overflowsWithPrevious
            activity = {
                ...activity,
                overflowsWithPrevious,
            }
            activities.push(activity)
            prevActivity = activity
        })
    return activities
})

const description = computed(() => {
    if (props.participant.meta?.description) {
        return props.participant.meta.description
    }
    return languagesStore.translateListValue(props.participant.module, 'moduleListSingular')
})
</script>

<style scoped lang="scss">
.scheduler-row {
    display: flex;
    align-items: center;
    width: 100%;
    height: 56px;
    position: relative;
}

.scheduler-row.border-top {
    .scheduler-participant,
    .scheduler-data {
        border-top: thin solid #dbdbdb;
    }
}

.scheduler-row.border-bottom {
    .scheduler-participant,
    .scheduler-data {
        border-bottom: thin solid #dbdbdb;
    }
}

.scheduler-participant-name {
    display: flex;
    flex-direction: column;
    overflow: hidden;

    .scheduler-participant-description {
        letter-spacing: 0.4px;
        font-size: 12px;
        color: #00000099;
        font-weight: 400;
    }
    > span {
        overflow: hidden;
        text-overflow: ellipsis;
    }
}

.left-col {
    width: 250px;
    min-width: 250px;
    max-width: 250px;
}

.scheduler-participant {
    width: 250px;
    min-width: 250px;
    max-width: 250px;
    font-size: 14px;
    font-weight: 600;
    color: rgb(var(--v-theme-secondary));
    height: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-decoration: none;
}

.scheduler-data {
    position: relative;
    flex-grow: 1;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
}

.scheduler-actions {
    height: 100%;
    width: 50px;
    min-width: 50px;
    max-width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
