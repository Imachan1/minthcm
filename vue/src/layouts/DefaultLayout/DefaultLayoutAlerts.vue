<template>
    <v-list class="py-4">
        <v-list-item v-for="alert in alerts.alerts" :key="alert.id">
            <div
                class="alert"
                v-ripple="{ class: 'text-primary' }"
                :class="{ 'alert-faded': alert.is_read }"
            >
                <div class="alert-body">
                    <span class="alert-title" v-text="alert.title" />
                    <span
                        class="alert-date"
                        v-text="toRelativeDate(alert.date)"
                    />
                </div>
                <div class="alert-nav">
                    <v-btn
                        class="alert-delete-btn"
                        icon="mdi-close"
                        variant="text"
                        density="comfortable"
                        color="secondary"
                        @click.stop="null"
                    />
                    <v-btn
                        v-if="!alert.is_read"
                        class="alert-not-read-dot"
                        icon="mdi-circle"
                        variant="text"
                        size="small"
                        density="compact"
                        color="error"
                        @click.stop="null"
                    />
                </div>
            </div>
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { useAlertsStore } from '@/store/alerts'

const alerts = useAlertsStore()

function toRelativeDate(date: string) {
    const dt = DateTime.fromSQL(date)
    if (dt.diffNow('days').days >= -5) {
        return dt.toRelative()
    }
    return dt.toFormat('dd.MM.yyyy')
}
</script>

<style scoped lang="scss">
.alert {
    display: flex;
    gap: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 300ms ease-in-out;

    &:hover {
        background: rgb(var(--v-theme-primary-light));
    }

    .alert-body {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 8px 12px;

        .alert-title {
            font-size: 16px;
            max-width: 25ch;
        }

        .alert-date {
            font-size: 12px;
            font-weight: 600;
            color: rgb(var(--v-theme-primary));
        }
    }

    .alert-nav {
        display: flex;
        gap: 4px;
        flex-direction: column;
        align-items: center;

        .alert-delete-btn {
            opacity: 0;
        }

        .alert-not-read-dot {
            opacity: 0.75;
            transition: all 150 ease-in-out;
            cursor: pointer;
            &:hover {
                opacity: 1;
            }
        }
    }

    &:hover .alert-delete-btn {
        opacity: 1;
    }
}
.alert-faded {
    opacity: 0.5;
}
</style>
