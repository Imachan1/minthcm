<template>
    <div class="list-header">
        <MintButton
            class="ms-auto"
            icon="mdi-playlist-plus"
            :text="languages.label('LBL_ESLIST_COLUMNS')"
            @click="showColumnsPopup"
        />
    </div>
</template>

<script setup lang="ts">
import MintButton from '@/components/MintButton.vue'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import ListViewColumnsPopup from './ListViewColumnsPopup.vue'

const store = useListViewStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()

function showColumnsPopup() {
    popups.showPopup({
        title: languages.label('LBL_ESLIST_COLUMNS_MANAGEMENT'),
        component: ListViewColumnsPopup,
        icon: 'mdi-playlist-plus',
    })
}
</script>

<style scoped lang="scss">
.list-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
}
</style>
