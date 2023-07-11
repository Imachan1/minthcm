<template>
    <v-card class="pt-4 pb-2">
        <v-list>
            <template v-if="alerts.sortedAlerts?.length">
                <v-list-item
                    v-for="alert in alerts.sortedAlerts"
                    :key="alert.id"
                >
                    <div
                        class="alert"
                        v-ripple="{ class: 'text-primary' }"
                        :class="{ 'alert-faded': alert.is_read }"
                        @click="redirectToAlert(alert)"
                    >
                        <div class="alert-body">
                            <span
                                class="alert-title"
                                v-text="alert.description"
                            />
                            <span
                                class="alert-date"
                                v-text="toRelativeDate(alert.date_entered)"
                            />
                        </div>
                        <div class="alert-nav">
                            <v-btn
                                class="alert-delete-btn"
                                icon="mdi-close"
                                variant="text"
                                density="comfortable"
                                color="secondary"
                                @click.stop="alerts.close(alert.id)"
                            />
                            <v-btn
                                v-if="!alert.is_read"
                                class="alert-not-read-dot"
                                icon="mdi-circle"
                                variant="text"
                                size="small"
                                density="compact"
                                color="error"
                                @click.stop="alerts.markRead(alert.id)"
                            />
                        </div>
                    </div>
                </v-list-item>
            </template>
            <span v-else v-text="languages.label('LBL_MINT4_NO_ALERTS')" class="px-4" />
        </v-list>
        <!-- <div class="alerts-footer">
            <v-tooltip text="Mark all as read" location="top left">
                <template v-slot:activator="{ props }">
                    <v-btn
                        v-bind="props"
                        color="secondary"
                        icon="mdi-email-open"
                        variant="text"
                        size="small"
                    />
                </template>
            </v-tooltip>
            <v-tooltip text="Delete all" location="top left">
                <template v-slot:activator="{ props }">
                    <v-btn
                        v-bind="props"
                        color="secondary"
                        icon="mdi-delete-sweep"
                        variant="text"
                        size="small"
                    />
                </template>
            </v-tooltip>
        </div> -->
    </v-card>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { DateTime } from 'luxon'
import { useAlertsStore, Alert } from '@/store/alerts'
import { useLanguagesStore } from '@/store/languages'
import { useUrlStore } from '@/store/url'

const router = useRouter()
const alerts = useAlertsStore()
const languages = useLanguagesStore()
const url = useUrlStore()

function toRelativeDate(date: string) {
    const dt = DateTime.fromSQL(date, { zone: 'UTC' })
    if (dt.diffNow('days').days >= -5) {
        return dt.toRelative()
    }
    return dt.toFormat('dd.MM.yyyy')
}

function redirectToAlert(alert: Alert) {
    if (!alert.is_read) {
        alerts.markRead(alert.id)
    }
    if (alert.url_redirect) {
        router.push(url.fromLegacyUrl(alert.url_redirect))
    }
}
</script>

<style scoped lang="scss">
.alerts-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.alert {
    display: flex;
    justify-content: space-between;
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
        gap: 2px;
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
