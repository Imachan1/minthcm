<template>
    <div v-if="!store.bean.isNew" class="header-panel">
        <div class="flex-container">
            <MintButton icon="mdi-arrow-left" @click="goBack" />
            <div class="name-container">
                <div class="module-name">{{ modules?.currentModule?.label }}</div>
                <div class="bean-name">
                    <div>{{ store.bean.name }}</div>
                    <MintButton
                        :icon="isFavorite ? 'mdi-heart-circle' : 'mdi-heart-outline'"
                        variant="text"
                        size="small"
                        @click="
                            isFavorite
                                ? favorites.removeFromFavorites(store.bean.module, store.bean.id)
                                : favorites.addToFavorites(store.bean.module, store.bean.id, store.bean.name)
                        "
                    />
                </div>
            </div>
            <v-menu v-if="actions.length" offset="16">
                <template v-slot:activator="{ props, isActive }">
                    <MintButton
                        class="ml-auto"
                        v-bind="props"
                        :active="isActive"
                        append-icon="mdi-menu-down"
                        :text="languages.label('LBL_ESLIST_ACTIONS')"
                    />
                </template>
                <MintMenuList :items="/*props.data.actions*/ actions || []" />
            </v-menu>
        </div>
        <div v-if="props.data?.fields?.length" class="fields-container">
            <div v-for="(row, i) in props.data.fields" class="row" :key="i">
                <div v-for="n in store.columns" :key="n - 1">
                    <Field
                        v-if="row[n - 1]"
                        :view="'detail'"
                        :defs="row[n - 1]"
                        :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                        :data="{ bean: store.bean.attributes }"
                        :modelValue="store.bean.syncAttributes[row[n - 1].name]"
                    />
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <h1>{{ modules?.currentModule?.label }}</h1>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useFavoritesStore } from '@/store/favorites'
import { FieldVardef, useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import DefaultLayout from '@/layouts/DefaultLayout/DefaultLayoutOnOffBoardingPopup.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import Field from '@/components/Fields/Field.vue'
import BeanActions from '@/business/BeanActions'

interface Props {
    data: {
        fields: Array<Array<FieldVardef>>
        actions?: ({
            name: string
            title?: string
            icon?: string
        } | string)[]
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const router = useRouter()
const favorites = useFavoritesStore()
const modules = useModulesStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()

const actions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []

    props.data.actions?.forEach((action) => {
        const actionName = typeof action === 'string' ? action : action.name
        const actionClass = BeanActions[actionName]
        if (typeof actionClass !== 'function') {
            console.warn(`Action ${actionName} not defined in BeanActions`)
            return
        }
        const actionObject = new actionClass(store.bean)
        if (actionObject.isAvailable()) {
            actions.push(actionObject.toMenuListItem())
        }
    })
    return actions
})

const goBack = () => {
    if (!router.options.history.state.back) {
        return router.push({ name: 'list', params: { module: modules.currentModule?.name } })
    }
    router.back()
}
const isFavorite = computed(() => favorites.isFavorite(store.bean.module, store.bean.id))

</script>

<style scoped lang="scss">
.header-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;

    .flex-container {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;

        .name-container {
            display: flex;
            flex-direction: column;

            .module-name {
                font-size: 12px;
                color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
            }

            .bean-name {
                display: flex;
                gap: 16px;
                align-items: center;
                font-size: 24px;
                font-weight: 600;
                line-height: 1;
            }
        }
    }

    .fields-container {
        border-top: 1px solid #dbdbdb;
        padding: 24px;
        display: flex;
        gap: 24px;
        flex-direction: column;

        .row {
            display: flex;
            gap: 24px;

            > * {
                flex-basis: calc(100% / 3);
            }
        }
    }
}
</style>
