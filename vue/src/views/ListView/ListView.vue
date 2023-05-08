<template>
    <div class="px-8 py-2">
        <h1 v-text="module" />
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
import ListViewFilters from './ListViewFilters.vue'

const url = useUrlStore()
const store = useListViewStore()
console.log('ListView init')

const module = computed(() => url.module)

onMounted(async () => {
    if (store.module !== module.value) {
        store.$reset()
    }
    await store.init()
})

onUnmounted(() => {
})

watch(module, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        console.log('lv watch')
        store.init()
    }
})

</script>

<style scoped lang="scss">
h1 {
    color: rgb(var(--v-theme-secondary-dark));
    letter-spacing: 1px;
    font-weight: 600;
}
.list-view {
    border-radius: 16px;
    padding-bottom: 8px;
    background: rgb(var(--v-theme-surface));
}
</style>
