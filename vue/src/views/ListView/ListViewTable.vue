<template>
    <v-data-table-server
        class="list-table"
        :headers="store.headers"
        :items="store.results"
        :loading="store.isLoading"
        height="calc(100vh - 300px)"
        fixed-header
        must-sort
    >
        <template
            v-for="link in store.customFields.links"
            v-slot:[`item.${link.nameField}`]="{ item }"
            :key="link.nameField"
        >
            <router-link
                v-if="item.raw[link.urlField]"
                :to="url.fromLegacyUrl(item.raw[link.urlField])"
                v-text="item.raw[link.nameField]"
            />
            <span v-else v-text="item.raw[link.nameField]" />
        </template>
        <template
            v-for="bool in store.customFields.booleans"
            v-slot:[`item.${bool}`]="{ item }"
            :key="bool"
        >
            <v-icon
                color="secondary"
                :icon="
                    item.raw[bool] && item.raw[bool] !== '0'
                        ? 'mdi-checkbox-marked-circle'
                        : 'mdi-close'
                "
            />
        </template>
        <template
            v-for="list in store.customFields.lists"
            v-slot:[`item.${list.field}`]="{ item }"
            :key="list.field"
        >
            <span v-text="list.options[item.raw[list.field]]" />
        </template>
        <template
            v-for="multienum in store.customFields.multienums"
            v-slot:[`item.${multienum.field}`]="{ item }"
            :key="multienum.field"
        >
            <span
                v-text="
                    formatMultienum(
                        item.raw[multienum.field],
                        multienum.options,
                    )
                "
            />
        </template>
        <template
            v-for="date in store.customFields.dates"
            v-slot:[`item.${date}`]="{ item }"
            :key="date"
        >
            <span v-text="formatDate(item.raw[date])" />
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <div class="d-flex justify-end" style="gap: 8px">
                <v-icon
                    v-for="action in getItemActions(item.raw)"
                    :key="action.icon"
                    @click="action.onClick(item.raw)"
                    color="secondary"
                    size="small"
                >
                    {{ action.icon }}
                </v-icon>
            </div>
        </template>
    </v-data-table-server>
</template>

<script setup lang="ts">
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { DateTime } from 'luxon'
import { useRouter } from 'vue-router'
import { useListViewStore } from './ListViewStore'
import { useUrlStore } from '@/store/url'

const router = useRouter()
const store = useListViewStore()
const url = useUrlStore()

const coreActions = {
    edit: {
        icon: 'mdi-pencil',
        onClick: (item) => router.push(`/${url.module}/EditView/${item.id}`),
    },
    view: {
        icon: 'mdi-eye',
        onClick: (item) => router.push(`/${url.module}/DetailView/${item.id}`),
    },
    delete: {
        icon: 'mdi-delete',
        onClick: (item) => null,
    },
}

function getItemActions(item: any) {
    return store.config.config.actions
        .filter(
            (action) => typeof action !== 'string' || item.acl_access[action],
        )
        .map((action) => {
            if (typeof action === 'string') {
                return coreActions[action]
            }
            return {
                ...action,
                onClick: (item) => eval(action.onClick)(item),
            }
        })
}

function formatDate(date: string) {
    if (!date) {
        return ''
    }
    if (date.length === 10) {
        // db date
        return DateTime.fromSQL(date).toFormat('dd.MM.yyyy') // todo: user format
    }
    if (date.length === 19) {
        // db datetime
        return DateTime.fromSQL(date).toFormat('dd.MM.yyyy HH:mm:ss') // todo: user format
    }
    return ''
}

function formatMultienum(value, labels) {
    return value
        .replaceAll('^', '')
        .split(',')
        .filter((label) => label in labels)
        .map((label) => labels[label])
        .join(', ')
}
</script>

<style scoped lang="scss">
.list-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }
}
</style>
