<template>
    <v-list class="mint-menu-list" nav density="compact" color="secondary">
        <v-list-item v-for="item in processedItems" :key="item.title" @click="item.onClick" :active="false" v-bind="item.url && item.url !== '/' ? { to: item.url } : { tag: 'button' }">
            <template v-if="item.icon" #prepend>
                <span style="font-size: 11px"><v-icon :icon="getIcon(item.icon)" /></span>
            </template>
            <v-list-item-title>
                {{ item.title }}
            </v-list-item-title>
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
import { usePopupsStore } from '@/store/popups'
import { computed } from 'vue'
import { popupComponents } from '../custom/components/MintPopups/CustomMintPopupsMap'

export interface MenuListOnClickActionData {
    type?: string
    componentName?: string
}

export interface MenuListItem {
    title: string
    icon?: string
    url?: string
    onClick?: () => void
    onClickActionData?: MenuListOnClickActionData
}

interface Props {
    items: MenuListItem[]
}
const props = defineProps<Props>()
const popups = usePopupsStore()

const processedItems = computed(() =>
  props.items.map((item) => {
    if (!item.url || item.url === '' || item.url === '/') {
      if (item?.onClickActionData?.type === 'popup' && item?.onClickActionData?.componentName) {
        item.onClick = () => {
          popups.showPopup(
            {
                title: item.title,
                component: popupComponents[item?.onClickActionData?.componentName ?? '']
            }
          )
        }
      }
    }

    return { ...item }
  })
)

//TODO: global function?
function getIcon(icon: string) {
    if (!icon) {
        return ''
    }
    if (icon.slice(0, 4) === 'mdi-') {
        return icon // Material Design Icons
    }
    if (icon.slice(0, 3) === 'fi-') {
        return icon // Flag Icons
    }
    return `mdi-${icon}` // return mdi by default
}
</script>

<style lang="scss">
.mint-menu-list {
    min-width: 186px;
    padding: 2px 0px;
    color: rgb(var(--v-theme-secondary));
    .v-list-item {
        margin: 0px;
        padding: 0px 12px;
        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
            background: rgb(var(--v-theme-primary-light));
        }
    }
    .v-list-item-title {
        font-size: 14px;
        font-weight: 600;
    }
    .v-icon {
        opacity: 1;
        margin-inline-end: 8px;
    }
}
</style>
