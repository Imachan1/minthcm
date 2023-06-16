<template>
    <div class="px-8 py-2">
        <h1 v-text="moduleName" />
        <div class="elevation-4 mt-1 list-view">
            <ListViewFilters />
            <ListViewHeader />
            <ListViewTable />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, watch, onMounted, onUnmounted } from 'vue'
import ListViewHeader from './ListViewHeader.vue'
import ListViewTable from './ListViewTable.vue'
import { useListViewStore } from './ListViewStore'
import { useUrlStore } from '@/store/url'
import { useLanguagesStore } from '@/store/languages'
import ListViewFilters from './ListViewFilters.vue'

const url = useUrlStore()
const store = useListViewStore()
const languages = useLanguagesStore()

const module = computed(() => url.module)
const moduleName = computed(() => languages.languages.app_list_strings?.moduleList?.[module.value])

onMounted(async () => {
    if (store.module !== module.value) {
        store.$reset()
    }
    await store.init()
})

onUnmounted(() => {
    store.$reset()
})

watch(module, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        store.init()
    }
})

</script>

<style scoped lang="scss">
h1 {
    letter-spacing: 1px;
    font-weight: 600;
}
.list-view {
    border-radius: 16px;
    padding-bottom: 8px;
    background: rgb(var(--v-theme-surface));
}
</style>
