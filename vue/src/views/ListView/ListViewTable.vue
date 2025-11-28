<template>
    <v-data-table-server
        class="list-table"
        :style="{
            minHeight: store.mode === 'relate' ? 'calc(100vh - 300px)' : 0,
        }"
        :headers="store.headers"
        :items="store.results"
        :items-length="store.itemsLength || 0"
        :loading="store.isLoading || store.initialLoading"
        fixed-header
        must-sort
        :show-select="store.itemsSelectable"
        v-model="store.selected"
        @update:options="store.options = $event"
        :no-data-text="
            store.error
                ? languages.label('LBL_ESLIST_FETCHING_DATA_ERROR')
                : languages.label('LBL_ESLIST_NO_DATA_AVAILABLE')
        "
        hover
    >
        <template v-slot:item.is_favorite="{ item }">
            <v-icon
                color="secondary"
                :icon="item.attributes.is_favorite ? 'mdi-heart' : 'mdi-heart-outline'"
                @click="store.toggleFavorite(item)"
                size="small"
                class="favorite-icon"
                v-ripple
            />
        </template>
        <template v-for="column in store.visibleColumns" v-slot:[`item.${column.name}`]="{ item }" :key="column.name">
            <Field
                view="list"
                :defs="
                    column.name === 'name'
                        ? Object.assign(store.defs.columns[column.name], { type: 'name' })
                        : store.defs.columns[column.name]
                "
                :data="{ bean: item }"
                :label="languages.label(store.defs.columns[column.name].label, store.module)"
                :options="item.logic.fieldsOptions[column.name]"
                :modelValue="item.attributes[column.name]"
            />
        </template>
        <template v-slot:item.actions="{ item }">
            <div class="d-flex justify-end" style="gap: 8px">
                <v-icon
                    v-for="action in getItemActions(item)"
                    v-show="action.icon"
                    :key="action.icon"
                    @click="action.onClick(item)"
                    color="secondary"
                    size="small"
                    :icon="action.icon"
                />
            </div>
        </template>
        <template #bottom>
            <VDataTableFooter
                :items-per-page-options="store.config?.config?.itemsPerPageOptions"
                v-bind:items-per-page-text="languages.label('LBL_ESLIST_ITEMS_PER_PAGE')"
                :page-text="pageText"
            />
        </template>
    </v-data-table-server>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useUrlStore } from '@/store/url'
import { usePopupsStore } from '@/store/popups'
import { mintApi } from '@/api/api'
import { useBackendStore } from '@/store/backend'
import Field from '@/components/Fields/Field.vue'

import ComponentLoader from '@/utils/componentLoader'

const router = useRouter()
const store = useListViewStore()
const url = useUrlStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()
const backend = useBackendStore()

const pageText = computed(() => {
    const pageText = `{0} - {1} ${languages.label('LBL_ESLIST_PAGE_TEXT')} {2}`
    return pageText
})

const coreActions = {
    edit: {
        icon: 'mdi-pencil',
        onClick: (item) => router.push(`/modules/${url.module}/EditView/${item.id}`),
    },
    view: {
        icon: 'mdi-eye',
        onClick: (item) => router.push(`/modules/${url.module}/DetailView/${item.id}`),
    },
    delete: {
        icon: 'mdi-delete',
        onClick: async (item) => {
            const confirmMessage = `${languages.label('LBL_ESLIST_DELETE_RECORD_CONFIRM_BODY')} ${item.name}?`
            if (await popups.confirm(confirmMessage)) {
                await mintApi.delete(`${url.module}/${item.id}`)
                store.getData()
            }
        },
    },
}

const customActionsModules = import.meta.glob('@/custom/views/ListView/Actions/*.ts', { eager: false })

const customActionsCache = new Map<
    string,
    {
        icon: string
        onClick: (item: any) => void | Promise<void>
        hasAccess?: (item: any) => boolean
    }
>()

const loadingActions = new Set<string>()

const actionsLoadedTrigger = ref(0)

async function loadCustomAction(actionName: string) {
    const cacheKey = `${url.module}-${actionName}`

    if (customActionsCache.has(cacheKey)) {
        return customActionsCache.get(cacheKey)
    }

    if (loadingActions.has(cacheKey)) {
        return null
    }

    loadingActions.add(cacheKey)

    const loader = Object.entries(customActionsModules).find(([path]) => path.endsWith(`/${actionName}.ts`))?.[1]

    if (!loader) {
        loadingActions.delete(cacheKey)
        return null
    }

    try {
        const actionModule = (await loader()) as { default: (context: any) => any }
        const action = actionModule.default
        const resolvedAction = action({ router, store, url, languages, popups, backend, ComponentLoader, mintApi })
        customActionsCache.set(cacheKey, resolvedAction)
        loadingActions.delete(cacheKey)
        actionsLoadedTrigger.value++
        return resolvedAction
    } catch (error) {
        console.error(`Failed to load custom action: ${actionName}`, error)
        loadingActions.delete(cacheKey)
        return null
    }
}

async function resolveAction(actionName: string) {
    const customAction = await loadCustomAction(actionName)
    if (customAction) {
        return customAction
    }

    return (coreActions as Record<string, any>)[actionName] ?? {}
}

function getItemActions(item: Record<string, unknown>) {
    actionsLoadedTrigger.value

    const actions = store.config.config.actions
        .filter((action: any) => typeof action !== 'string' || (item.aclAccess as any)[action])
        .map((action: any) => {
            const actionName = action.action || action
            if (typeof actionName === 'string') {
                const cached = customActionsCache.get(`${url.module}-${actionName}`)
                if (cached) {
                    if (cached.hasAccess && !cached.hasAccess(item)) {
                        return null
                    }
                    return cached
                }

                const coreAction = (coreActions as any)[actionName]

                if (!coreAction) {
                    loadCustomAction(actionName)
                    return {
                        icon: 'mdi-loading',
                        onClick: async (item: any) => {
                            const resolvedAction = await resolveAction(actionName)
                            if (resolvedAction?.onClick) {
                                await resolvedAction.onClick(item)
                            }
                        },
                    }
                }

                return coreAction
            }
            return {
                ...action,
                onClick: (item: any) => eval(action.onClick)(item),
            }
        })
        .filter((action: any) => action !== null)

    return actions
}
</script>

<style scoped lang="scss">
.list-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-pagination__first),
    :deep(.v-pagination__last) {
        display: none;
    }
    .favorite-icon {
        position: relative;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 40px;
        min-height: 40px;

        &:hover {
            background-color: rgba(var(--v-theme-on-surface), 0.04);
        }
        &:focus-visible {
            background-color: rgba(var(--v-theme-on-surface), 0.12);
            outline: 2px solid rgb(var(--v-theme-primary));
            outline-offset: 2px;
        }
        &:active {
            background-color: rgba(var(--v-theme-on-surface), 0.16);
        }
    }
}
</style>
